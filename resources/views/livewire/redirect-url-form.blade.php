<div class="max-w-3xl mx-auto p-6 bg-gray-800 rounded-xl shadow-md space-y-6 text-white">
    <h2 class="text-2xl font-semibold">Redirecionamento de URL</h2>

    @if (session()->has('success'))
        <div class="text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-300">URL para encurtar</label>
            <input type="url" wire:model.defer="redirect_url" placeholder="https://example.com" required
                   class="w-full px-4 py-2 rounded bg-gray-900 text-white border border-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500" />
            @error('redirect_url') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded">
            Salvar
        </button>
    </form>

    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-4">Lista de URLs encurtadas com redirecionamento</h3>

        @if($redirects->count())
            <div class="overflow-x-auto">
                <table id="redirects-table" class="min-w-full bg-gray-900 rounded text-white">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left">Código</th>
                            <th class="px-4 py-2 text-left">Status</th>
                            <th class="px-4 py-2 text-left">Url destino</th>
                            <th class="px-4 py-2 text-left">Último acesso</th>
                            <th class="px-4 py-2 text-left">Criado em</th>
                            <th class="px-4 py-2 text-left">Atualizado em</th>
                            <th class="px-4 py-2 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($redirects as $redirect)
                            <tr class="border-b border-gray-700">
                                <td class="px-4 py-2">{{ $redirect->code }}</td>
                                <td class="px-4 py-2">
                                    <button
                                        wire:click="toggleActive('{{ $redirect->id }}')"
                                        class="flex items-center gap-1 px-2 py-1 rounded focus:outline-none transition
                                            {{ $redirect->active ? 'bg-green-700 hover:bg-green-800' : 'bg-gray-700 hover:bg-gray-800' }}"
                                        title="{{ $redirect->active ? 'Desativar' : 'Ativar' }}"
                                    >
                                        @if($redirect->active)
                                            <!-- Ícone de ligado (Heroicons: check-circle) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2l4 -4m5 2a9 9 0 1 1 -18 0a9 9 0 0 1 18 0z" />
                                            </svg>
                                            <span>Ativo</span>
                                        @else
                                            <!-- Ícone de desligado (Heroicons: x-circle) -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 1 1 -18 0a9 9 0 0 1 18 0z" />
                                            </svg>
                                            <span>Inativo</span>
                                        @endif
                                    </button>
                                </td>
                                <td class="px-4 py-2 break-all">
                                    @if($editId === $redirect->id)
                                        <input type="url" wire:model.defer="edit_url" class="w-full px-2 py-1 rounded bg-gray-700 text-white border border-gray-500" />
                                    @else
                                        <a href="{{ route('redirects.redirect', ['code' => $redirect->code]) }}" target="_blank" class="text-blue-400 hover:underline">{{ $redirect->redirect_url }}</a>
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    {{ optional($redirect->getLastAccess()->created_at)->format('d M Y, H:i') ?? 'Ainda não acessado' }}
                                </td>
                                <td class="px-4 py-2">{{ $redirect->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-2">{{ $redirect->updated_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    @if($editId === $redirect->id)
                                        <button wire:click="update" class="text-green-400 hover:underline">Salvar</button>
                                        <button wire:click="cancelEdit" class="text-yellow-400 hover:underline">Cancelar</button>
                                    @else
                                        <button wire:click="edit('{{ $redirect->id }}')" class="text-blue-400 hover:underline">Editar</button>
                                        <button wire:click="delete('{{ $redirect->id }}')" class="text-red-400 hover:underline">Excluir</button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $redirects->links('pagination::tailwind') }}
            </div>
        @else
            <p class="text-gray-400">No redirect links registered yet.</p>
        @endif
    </div>
</div>
