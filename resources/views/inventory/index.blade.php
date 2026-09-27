<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto" x-data="salesApp()">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Panel de Inventario y Ventas</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Inventario de Productos -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Productos Disponibles</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Producto</th>
                            <th class="py-2">Precio</th>
                            <th class="py-2">Stock</th>
                            <th class="py-2">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($products) > 0): ?>
                            <?php foreach ($products as$product): ?>
                                <tr class="border-b">
                                    <td class="py-2 font-medium"><?php echo e($product->name); ?></td>
                                    <td class="py-2">$<?php echo e(number_format($product->price, 2)); ?></td>
                                    <td class="py-2 text-blue-600 font-bold"><?php echo e($product->stock); ?></td>
                                    <td class="py-2">
                                        <button 
                                            @click="addToCart(<?php echo e($product->id); ?>, '<?php echo e(addslashes($product->name)); ?>', <?php echo e($product->price); ?>, <?php echo e($product->stock); ?>)"
                                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                            Agregar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="py-4 text-center text-gray-500">No hay productos en la base de datos.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Carrito y Nueva Venta -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4 text-gray-700">Punto de Venta</h2>
                
                <template x-if="cart.length === 0">
                    <p class="text-gray-500 italic">No hay productos seleccionados.</p>
                </template>

                <template x-if="cart.length > 0">
                    <div>
                        <ul class="divide-y divide-gray-200 mb-4">
                            <template x-for="(item, index) in cart" :key="item.product_id">
                                <li class="py-2 flex justify-between items-center">
                                    <div>
                                        <p class="font-medium text-gray-800" x-text="item.name"></p>
                                        <p class="text-sm text-gray-500" x-text="'$' + item.price + ' x ' + item.quantity"></p>
                                    </div>
                                    <button @click="removeFromCart(index)" class="text-red-500 text-sm font-semibold">Quitar</button>
                                </li>
                            </template>
                        </ul>

                        <button 
                            @click="processSale()" 
                            :disabled="loading"
                            class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                            <span x-text="loading ? 'Procesando...' : 'Confirmar Venta'"></span>
                        </button>
                    </div>
                </template>

                <!-- Mensaje de Estado -->
                <div x-show="message" class="mt-4 p-3 rounded text-sm font-medium" :class="isError ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'" x-text="message"></div>
            </div>
        </div>
    </div>

    <script>
        function salesApp() {
            return {
                cart: [],
                loading: false,
                message: '',
                isError: false,

                addToCart(id, name, price, maxStock) {
                    let existing = this.cart.find(i => i.product_id === id);
                    if (existing) {
                        if (existing.quantity < maxStock) {
                            existing.quantity++;
                        } else {
                            alert('Stock máximo alcanzado en carrito');
                        }
                    } else {
                        if (maxStock > 0) {
                            this.cart.push({ product_id: id, name: name, price: price, quantity: 1 });
                        } else {
                            alert('Producto sin stock');
                        }
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                async processSale() {
                    this.loading = true;
                    this.message = '';

                    const payload = {
                        items: this.cart.map(i => ({ product_id: i.product_id, quantity: i.quantity }))
                    };

                    try {
                        const response = await fetch('/api/sales', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.isError = false;
                            this.message = `Venta #${data.data.sale_id} exitosa! Total: $${data.data.net_total}`;
                            this.cart = [];
                            setTimeout(() => location.reload(), 2000);
                        } else {
                            this.isError = true;
                            this.message = data.error || data.message || 'Error al procesar venta';
                        }
                    } catch (e) {
                        this.isError = true;
                        this.message = 'Error de conexión con el servidor';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>