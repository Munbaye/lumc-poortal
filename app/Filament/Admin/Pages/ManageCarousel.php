<?php

namespace App\Filament\Admin\Pages;

use App\Models\CarouselImage;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class ManageCarousel extends Page implements HasForms
{
    use InteractsWithForms, WithFileUploads;

    protected static ?string $navigationIcon  = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Carousel Images';
    protected static ?string $title           = 'Manage Carousel Images';
    protected static ?string $navigationGroup = 'Landing Page';
    protected static ?int    $navigationSort  = 1;

    protected static string $view = 'filament.admin.pages.manage-carousel';

    // Upload state
    public array  $upload   = [];
    public string $newLabel = '';

    // Modal flags
    public bool   $showDeleteModal    = false;
    public bool   $showRestoreModal   = false;
    public bool   $showUploadConfirm  = false;
    public bool   $showBulkModal      = false; // bulk action confirm
    public ?int   $pendingDeleteId    = null;
    public string $pendingDeleteLabel = '';

    // Bulk selection
    public array  $selectedIds  = [];
    public string $bulkAction   = ''; // 'delete' | 'hide' | 'show'

    // ── Mount ─────────────────────────────────────────────────────────────────
    public function mount(): void
    {
        if (CarouselImage::count() === 0) {
            $this->seedStaticImages();
        }
    }

    public function seedStaticImages(): void
    {
        Storage::disk('public')->makeDirectory('carousel');
        $statics = [
            ['file' => 'lumc-logo.png',            'label' => 'LUMC Logo'],
            ['file' => 'province-logo.png',         'label' => 'Province of La Union'],
            ['file' => 'bagong-pilipinas-logo.png', 'label' => 'Bagong Pilipinas'],
            ['file' => 'agkaysa.png',               'label' => 'Agkaysa!'],
        ];
        foreach ($statics as $i => $item) {
            $srcPath = public_path('images/' . $item['file']);
            if (!file_exists($srcPath)) continue;
            $ext      = pathinfo($item['file'], PATHINFO_EXTENSION);
            $filename = Str::uuid() . '.' . $ext;
            Storage::disk('public')->put('carousel/' . $filename, file_get_contents($srcPath));
            CarouselImage::create([
                'filename'   => $filename,
                'label'      => $item['label'],
                'sort_order' => $i + 1,
                'is_active'  => true,
            ]);
        }
    }

    // ── Upload ────────────────────────────────────────────────────────────────
    public function prepareUpload(): void
    {
        if (empty($this->upload)) {
            Notification::make()->title('Please select at least one image first.')->warning()->send();
            return;
        }
        $this->showUploadConfirm = true;
    }

    public function confirmUpload(): void
    {
        $this->validate([
            'upload'   => 'required',
            'upload.*' => 'image|max:10240',
        ]);

        Storage::disk('public')->makeDirectory('carousel');
        $nextOrder = (CarouselImage::max('sort_order') ?? 0) + 1;
        $count     = 0;

        foreach ($this->upload as $file) {
            $ext      = $file->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $ext;
            $file->storeAs('carousel', $filename, 'public');
            CarouselImage::create([
                'filename'   => $filename,
                'label'      => $this->newLabel ?: null,
                'sort_order' => $nextOrder++,
                'is_active'  => true,
            ]);
            $count++;
        }

        $this->upload            = [];
        $this->newLabel          = '';
        $this->showUploadConfirm = false;
        $this->dispatch('carousel-upload-done');

        Notification::make()
            ->title($count . ' image' . ($count > 1 ? 's' : '') . ' uploaded successfully!')
            ->success()->send();
    }

    public function cancelUpload(): void { $this->showUploadConfirm = false; }

    // ── Single delete ─────────────────────────────────────────────────────────
    public function confirmDeleteImage(int $id): void
    {
        $img = CarouselImage::find($id);
        $this->pendingDeleteId    = $id;
        $this->pendingDeleteLabel = $img?->label ?? 'this image';
        $this->showDeleteModal    = true;
    }

    public function executeDelete(): void
    {
        if (!$this->pendingDeleteId) return;
        $img = CarouselImage::find($this->pendingDeleteId);
        if ($img) {
            Storage::disk('public')->delete('carousel/' . $img->filename);
            $img->delete();
        }
        $this->pendingDeleteId    = null;
        $this->pendingDeleteLabel = '';
        $this->showDeleteModal    = false;
        Notification::make()->title('Image deleted.')->warning()->send();
    }

    public function cancelDelete(): void
    {
        $this->pendingDeleteId    = null;
        $this->pendingDeleteLabel = '';
        $this->showDeleteModal    = false;
    }

    // ── Toggle visibility ─────────────────────────────────────────────────────
    public function toggleActive(int $id): void
    {
        $img = CarouselImage::findOrFail($id);
        $img->update(['is_active' => !$img->is_active]);
        Notification::make()
            ->title('Image is now ' . ($img->is_active ? 'visible' : 'hidden') . ' on the landing page.')
            ->success()->send();
    }

    // ── Bulk actions ──────────────────────────────────────────────────────────
    public function openBulkAction(string $action): void
    {
        if (empty($this->selectedIds)) {
            Notification::make()->title('Select at least one image first.')->warning()->send();
            return;
        }
        $this->bulkAction    = $action;
        $this->showBulkModal = true;
    }

    public function cancelBulk(): void
    {
        $this->showBulkModal = false;
        $this->bulkAction    = '';
    }

    public function executeBulk(): void
    {
        $ids = $this->selectedIds;

        if ($this->bulkAction === 'delete') {
            foreach (CarouselImage::whereIn('id', $ids)->get() as $img) {
                Storage::disk('public')->delete('carousel/' . $img->filename);
                $img->delete();
            }
            Notification::make()->title(count($ids) . ' image(s) deleted.')->warning()->send();
        } elseif ($this->bulkAction === 'hide') {
            CarouselImage::whereIn('id', $ids)->update(['is_active' => false]);
            Notification::make()->title(count($ids) . ' image(s) hidden.')->success()->send();
        } elseif ($this->bulkAction === 'show') {
            CarouselImage::whereIn('id', $ids)->update(['is_active' => true]);
            Notification::make()->title(count($ids) . ' image(s) set to visible.')->success()->send();
        }

        $this->selectedIds   = [];
        $this->bulkAction    = '';
        $this->showBulkModal = false;
    }

    public function toggleSelect(int $id): void
    {
        if (in_array($id, $this->selectedIds)) {
            $this->selectedIds = array_values(array_filter($this->selectedIds, fn($x) => $x !== $id));
        } else {
            $this->selectedIds[] = $id;
        }
    }

    public function clearSelection(): void
    {
        $this->selectedIds = [];
    }

    // ── Drag-reorder (called by JS after drag ends) ───────────────────────────
    public function reorder(array $orderedIds): void
    {
        foreach ($orderedIds as $position => $id) {
            CarouselImage::where('id', $id)->update(['sort_order' => $position + 1]);
        }
        Notification::make()->title('Order saved.')->success()->send();
    }

    // ── Arrow reorder ─────────────────────────────────────────────────────────
    public function moveUp(int $id): void   { $this->swap($id, 'up');   }
    public function moveDown(int $id): void { $this->swap($id, 'down'); }

    private function swap(int $id, string $direction): void
    {
        $all     = CarouselImage::orderBy('sort_order')->orderBy('id')->get();
        $index   = $all->search(fn($i) => $i->id === $id);
        if ($index === false) return;
        $swapIdx = $direction === 'up' ? $index - 1 : $index + 1;
        if ($swapIdx < 0 || $swapIdx >= $all->count()) return;
        $a = $all[$index];
        $b = $all[$swapIdx];
        if ($a->sort_order === $b->sort_order) {
            $a->sort_order = $index + 1;
            $b->sort_order = $swapIdx + 1;
        } else {
            [$a->sort_order, $b->sort_order] = [$b->sort_order, $a->sort_order];
        }
        $a->save();
        $b->save();
        Notification::make()->title('Order updated.')->success()->send();
    }

    // ── Label ─────────────────────────────────────────────────────────────────
    public function updateLabel(int $id, string $label): void
    {
        CarouselImage::findOrFail($id)->update(['label' => $label ?: null]);
        Notification::make()->title('Label saved.')->success()->send();
    }

    // ── Restore defaults ──────────────────────────────────────────────────────
    public function askRestore(): void    { $this->showRestoreModal = true;  }
    public function cancelRestore(): void { $this->showRestoreModal = false; }

    public function executeRestore(): void
    {
        foreach (CarouselImage::all() as $img) {
            Storage::disk('public')->delete('carousel/' . $img->filename);
        }
        CarouselImage::truncate();
        $this->seedStaticImages();
        $this->selectedIds      = [];
        $this->showRestoreModal = false;
        Notification::make()->title('Carousel restored to the original 4 default images!')->success()->send();
    }

    // ── View helper ───────────────────────────────────────────────────────────
    public function getImages()
    {
        return CarouselImage::orderBy('sort_order')->orderBy('id')->get();
    }
}