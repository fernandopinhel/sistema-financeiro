{{-- delete-modal.blade.php --}}
<div x-show="openDelete" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
     style="display: none;">
    
    {{-- Container do Modal --}}
    <div @click.away="openDelete = false" 
         class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl border border-slate-100 transition-all">
        
        <h2 class="text-lg font-bold text-slate-900 mb-2">Excluir Transação</h2>
        <p class="text-sm text-slate-500 mb-5">
            Tem certeza que deseja excluir a transação <span class="font-semibold text-slate-800">"{{ $label }}"</span>? Esta ação não poderá ser desfeita.
        </p>
        
        <form action="{{ $route }}" method="POST">
            @csrf
            @method('DELETE')
            
            <div class="flex items-center justify-end gap-2.5">
                <button type="button" 
                        @click="openDelete = false" 
                        class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                
                <button type="submit" 
                        class="px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors shadow-sm">
                    Confirmar Exclusão
                </button>
            </div>
        </form>
    </div>
</div>