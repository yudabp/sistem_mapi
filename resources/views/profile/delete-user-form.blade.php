<x-action-section>
    <x-slot name="title">
        Hapus Akun
    </x-slot>

    <x-slot name="description">
        Hapus akun Anda secara permanen.
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.
        </div>

        <div class="mt-5">
            <x-danger-button wire:click="confirmUserDeletion" wire:loading.attr="disabled">
                Hapus Akun
            </x-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-dialog-modal wire:model.live="confirmingUserDeletion">
            <x-slot name="title">
                Hapus Akun
            </x-slot>

            <x-slot name="content">
                Apakah Anda yakin ingin menghapus akun Anda? Setelah akun dihapus, semua sumber daya dan data akan dihapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.

                <div class="mt-4" x-data="{}" x-on:confirming-delete-user.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password" class="mt-1 block w-3/4"
                                autocomplete="current-password"
                                placeholder="Kata Sandi"
                                x-ref="password"
                                wire:model.live="password"
                                wire:keydown.enter="deleteUser" />

                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    Batal
                </x-secondary-button>

                <x-danger-button class="ms-3" wire:click="deleteUser" wire:loading.attr="disabled">
                    Hapus Akun
                </x-danger-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
