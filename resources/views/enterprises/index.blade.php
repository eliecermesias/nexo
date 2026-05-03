<x-layouts::app>
    <div class="mb-4 flex items-center justify-between">
        <flux:breadcrumbs >
        <flux:breadcrumbs.item :href="route('dashboard')">Dashboard</flux:breadcrumbs.item>        
        <flux:breadcrumbs.item>{{ __("Enterprises") }}</flux:breadcrumbs.item>
        </flux:breadcrumbs>
    </div>
     <div class="relative overflow-x-auto mb-4">
        <table  class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead  class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <th scope="col" class="px-6 py-3">id</th>
                <th scope="col" class="px-6 py-3">Nombre</th>
                <th scope="col" class="px-6 py-3">Correo</th>
                <th scope="col" class="px-6 py-3 "width="300px">Edición</th>
            </thead>
            <tbody>
                @foreach ($enterprises as $enterprise)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">    
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$enterprise->id}}
                        </td> 
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$enterprise->name}}
                        </td> 
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{$enterprise->email}}
                        </td> 
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{route('enterprises.edit', $enterprise)}}" class= "btn btn-yellow text-xs">Editar</a>
                                <form action="{{route('enterprises.destroy', $enterprise)}}" class="delete-form" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-yellow text-xs">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                            
                        </td>
                    </tr>

                @endforeach
            </tbody>
        </table>
    </div>

</x-layouts::app>
