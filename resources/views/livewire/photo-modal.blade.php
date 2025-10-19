@push('styles')
<style>
    .modal-backdrop {
        backdrop-filter: blur(4px);
    }
    
    .photo-container {
        max-height: 90vh;
        max-width: 90vw;
    }
    
    .photo-container img {
        max-height: 85vh;
        max-width: 85vw;
        object-fit: contain;
    }
    
    .loading-spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #8b5cf6;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .zoom-controls {
        transition: opacity 0.3s ease;
    }
    
    .photo-container:hover .zoom-controls {
        opacity: 1;
    }
</style>
@endpush

<div>
    <!-- Modal Backdrop -->
    @if($show)
        <div 
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            wire:click="closeModal"
            wire:keydown.escape="closeModal"
        >
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black bg-opacity-50 modal-backdrop"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden z-10" wire:click.stop>
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                    <button 
                        wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 transition-colors"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Photo Container -->
                <div class="photo-container relative bg-gray-100 flex items-center justify-center p-4">
                    @if($loading)
                        <div class="loading-spinner"></div>
                    @endif
                    
                    <img 
                        src="{{ $photoUrl }}" 
                        alt="{{ $title }}"
                        class="rounded-lg shadow-lg transition-transform duration-300"
                        wire:load="onPhotoLoad"
                        x-data="{
                            zoom: 1,
                            zoomIn() {
                                this.zoom = Math.min(this.zoom + 0.25, 3);
                                this.applyZoom();
                            },
                            zoomOut() {
                                this.zoom = Math.max(this.zoom - 0.25, 0.5);
                                this.applyZoom();
                            },
                            resetZoom() {
                                this.zoom = 1;
                                this.applyZoom();
                            },
                            applyZoom() {
                                this.$el.style.transform = `scale(${this.zoom})`;
                            }
                        }"
                        x-init="resetZoom()"
                    />
                    
                    <!-- Zoom Controls -->
                    <div class="zoom-controls absolute bottom-4 right-4 flex space-x-2 opacity-0">
                        <button 
                            @click="zoomOut()"
                            class="bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 rounded-full p-2 shadow-lg transition-all"
                            title="Zoom Out"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                            </svg>
                        </button>
                        <button 
                            @click="resetZoom()"
                            class="bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 rounded-full p-2 shadow-lg transition-all"
                            title="Reset Zoom"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 0h-4m4 0l-5-5"></path>
                            </svg>
                        </button>
                        <button 
                            @click="zoomIn()"
                            class="bg-white bg-opacity-90 hover:bg-opacity-100 text-gray-700 rounded-full p-2 shadow-lg transition-all"
                            title="Zoom In"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex items-center justify-between p-4 border-t border-gray-200 bg-gray-50">
                    <div class="text-sm text-gray-500">
                        Click outside or press ESC to close
                    </div>
                    <div class="flex space-x-2">
                        <button 
                            wire:click="closeModal"
                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        // Handle keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                Livewire.dispatch('closeModal');
            }
        });
    });
</script>
@endpush