<?php
namespace App\Filament\Admin\Resources;

use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model           = ActivityLog::class;
    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Activity Logs';
    protected static ?string $pluralLabel     = 'Activity Logs';
    protected static ?string $navigationGroup = 'Audit';
    protected static ?int    $navigationSort  = 10;

    public static function canCreate(): bool   { return false; }
    public static function canEdit($r): bool   { return false; }
    public static function canDelete($r): bool { return false; }

    // ── Safely decode raw DB JSON string OR array → PHP array ────────────────
    private static function toArray(mixed $state): array
    {
        if (is_array($state))  return $state;
        if (!$state)           return [];
        if (is_string($state)) {
            $decoded = json_decode($state, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public static function table(Table $table): Table
    {
        // ── NO ->query() here. ────────────────────────────────────────────────
        // The query is owned entirely by ListActivityLogs::getTableQuery().
        // Putting ->query() here AND having getTableQuery() on the page
        // causes a null-model conflict during Livewire re-hydration.
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date / Time')
                    ->dateTime('M d, Y H:i:s')
                    ->sortable()
                    ->description(fn ($record) => $record->created_at->diffForHumans())
                    ->width('165px'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->placeholder('System / Guest')
                    ->description(fn ($record) => $record->panel
                        ? ucfirst($record->panel) . ' panel'
                        : null
                    )
                    ->weight('medium'),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'auth'     => 'Authentication',
                        'patient'  => 'Patient Registry',
                        'vitals'   => 'Vital Signs',
                        'clinical' => 'Clinical',
                        'orders'   => "Doctor's Orders",
                        'uploads'  => 'Lab Uploads',
                        'admin'    => 'User Management',
                        default    => ucfirst($state ?? 'System'),
                    })
                    ->color(fn ($state) => match ($state) {
                        'auth'     => 'info',
                        'patient'  => 'primary',
                        'vitals'   => 'warning',
                        'clinical' => 'success',
                        'orders'   => 'gray',
                        'uploads'  => 'purple',
                        'admin'    => 'danger',
                        default    => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'login'               => 'Logged In',
                        'logout'              => 'Logged Out',
                        'login_failed'        => 'Login Failed',
                        'created_patient'     => 'Patient Created',
                        'updated_patient'     => 'Patient Updated',
                        'deleted_patient'     => 'Patient Deleted',
                        'restored_patient'    => 'Patient Restored',
                        'recorded_vitals'     => 'Vitals Recorded',
                        'assessed_patient'    => 'Assessment Saved',
                        'admitted_patient'    => 'Patient Admitted',
                        'discharged_patient'  => 'Patient Discharged',
                        'added_order'         => 'Order Added',
                        'completed_order'     => 'Order Completed',
                        'uploaded_result'     => 'Result Uploaded',
                        'created_user'        => 'User Created',
                        'updated_user'        => 'User Updated',
                        'deleted_user'        => 'User Deleted',
                        'toggled_user_active' => 'User Toggled Active',
                        default               => ucwords(str_replace('_', ' ', $state ?? '')),
                    })
                    ->color(fn ($state) => match ($state) {
                        'login'               => 'success',
                        'logout'              => 'gray',
                        'login_failed'        => 'danger',
                        'created_patient'     => 'primary',
                        'updated_patient'     => 'warning',
                        'deleted_patient'     => 'danger',
                        'restored_patient'    => 'info',
                        'recorded_vitals'     => 'warning',
                        'assessed_patient',
                        'admitted_patient'    => 'success',
                        'discharged_patient'  => 'gray',
                        'uploaded_result'     => 'purple',
                        'created_user'        => 'primary',
                        'updated_user'        => 'warning',
                        'deleted_user'        => 'danger',
                        default               => 'gray',
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject_label')
                    ->label('Record')
                    ->searchable()
                    ->placeholder('—')
                    ->description(fn ($record) => $record->subject_type
                        ? $record->subject_type . ' #' . ($record->subject_id ?? '?')
                        : null
                    ),

                Tables\Columns\TextColumn::make('new_values')
                    ->label('Details')
                    ->formatStateUsing(function ($state) {
                        $data = self::toArray($state);
                        if (empty($data)) return '—';
                        $parts = [];
                        foreach (array_slice($data, 0, 3) as $k => $v) {
                            if (is_array($v)) $v = json_encode($v);
                            $v = (string) $v;
                            $parts[] = ucwords(str_replace('_', ' ', $k)) . ': '
                                . (strlen($v) > 35 ? substr($v, 0, 35) . '…' : $v);
                        }
                        $extra = count($data) > 3 ? ' (+' . (count($data) - 3) . ' more)' : '';
                        return implode(' · ', $parts) . $extra;
                    })
                    ->wrap()
                    ->color('gray')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->fontFamily('mono')
                    ->color('gray')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')
                            ->label('From')
                            ->native(false)
                            ->default(today()->subDays(7)),
                        \Filament\Forms\Components\DatePicker::make('until')
                            ->label('Until')
                            ->native(false)
                            ->default(today()),
                    ])
                    ->query(function (Builder $q, array $data): Builder {
                        return $q
                            ->when($data['from'],  fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['until'], fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    })
                    ->indicateUsing(function (array $data): array {
                        $out = [];
                        if ($data['from'])  $out[] = 'From: '  . \Carbon\Carbon::parse($data['from'])->format('M d, Y');
                        if ($data['until']) $out[] = 'Until: ' . \Carbon\Carbon::parse($data['until'])->format('M d, Y');
                        return $out;
                    }),

                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->options([
                        'auth'     => 'Authentication',
                        'patient'  => 'Patient Registry',
                        'vitals'   => 'Vital Signs',
                        'clinical' => 'Clinical',
                        'orders'   => "Doctor's Orders",
                        'uploads'  => 'Lab Uploads',
                        'admin'    => 'User Management',
                        'system'   => 'System',
                    ]),

                Tables\Filters\SelectFilter::make('action')
                    ->label('Action')
                    ->options([
                        'login'               => 'Logged In',
                        'logout'              => 'Logged Out',
                        'login_failed'        => 'Login Failed',
                        'created_patient'     => 'Patient Created',
                        'updated_patient'     => 'Patient Updated',
                        'deleted_patient'     => 'Patient Deleted',
                        'recorded_vitals'     => 'Vitals Recorded',
                        'assessed_patient'    => 'Assessment Saved',
                        'admitted_patient'    => 'Patient Admitted',
                        'discharged_patient'  => 'Patient Discharged',
                        'added_order'         => 'Order Added',
                        'completed_order'     => 'Order Completed',
                        'uploaded_result'     => 'Result Uploaded',
                        'created_user'        => 'User Created',
                        'updated_user'        => 'User Updated',
                        'deleted_user'        => 'User Deleted',
                    ]),

                Tables\Filters\SelectFilter::make('panel')
                    ->label('Panel')
                    ->options([
                        'admin'  => 'Admin',
                        'doctor' => 'Doctor',
                        'nurse'  => 'Nurse',
                        'clerk'  => 'Clerk',
                        'tech'   => 'Tech',
                    ]),

                Tables\Filters\SelectFilter::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->actions([
                Tables\Actions\Action::make('view_changes')
                    ->label('View Changes')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading(fn ($record) => match ($record->action) {
                        'login'              => 'Logged In',
                        'logout'             => 'Logged Out',
                        'login_failed'       => 'Login Failed',
                        'created_patient'    => 'Patient Created',
                        'updated_patient'    => 'Patient Updated',
                        'recorded_vitals'    => 'Vitals Recorded',
                        'assessed_patient'   => 'Assessment Saved',
                        'admitted_patient'   => 'Patient Admitted',
                        'discharged_patient' => 'Patient Discharged',
                        default              => ucwords(str_replace('_', ' ', $record->action)),
                    } . ' — Detail')
                    ->modalContent(fn ($record) => view(
                        'filament.admin.pages.activity-log-detail',
                        ['log' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->visible(fn ($record) => $record->old_values || $record->new_values),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ActivityLogResource\Pages\ListActivityLogs::route('/'),
        ];
    }
}