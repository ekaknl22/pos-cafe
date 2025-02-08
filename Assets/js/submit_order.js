    let orderItems = [];
    let subtotal = 0;
    const taxRate = 0.1;

    function addToCart(itemName, itemPrice) {
        const existingItem = orderItems.find(item => item.name === itemName);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            orderItems.push({ name: itemName, price: itemPrice, quantity: 1 });
        }
        subtotal += itemPrice;
        updateOrderSummary();
    }

    function updateOrderSummary() {
    const orderItemsContainer = document.querySelector('.tempat-order');
    const subtotalElement = document.getElementById('subtotal');
    const taxElement = document.getElementById('tax');
    const totalElement = document.getElementById('total');
    if (!orderItemsContainer) {
        console.error('Container untuk Order Summary tidak ditemukan.');
    }
    // Hapus semua item lama
    orderItemsContainer.innerHTML = '';

    if (orderItems.length === 0) {
        // Jika tidak ada item, tampilkan pesan default
        orderItemsContainer.innerHTML = '<p class="text-center">---No item selected---</p>';
    } else {
        // Tambahkan setiap item ke ringkasan
        orderItems.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('d-flex', 'justify-content-between', 'mb-2');
            itemElement.innerHTML = `
                <div>
                    <button class="btn btn-sm btn-secondary me-2" onclick="decreaseQuantity(${index})">-</button>
                    ${item.name} (x${item.quantity})
                    <button class="btn btn-sm btn-secondary ms-2" onclick="increaseQuantity(${index})">+</button>
                </div>
                <p>Rp ${(item.price * item.quantity).toLocaleString('id-ID')}</p>
            `;
            orderItemsContainer.appendChild(itemElement);
        });
    }

    // Hitung subtotal, pajak, dan total
    const tax = subtotal * taxRate;
    const total = subtotal + tax;

    // Update elemen teks di halaman
    subtotalElement.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;
    taxElement.textContent = `Rp ${tax.toLocaleString('id-ID')}`;
    totalElement.textContent = `Rp ${total.toLocaleString('id-ID')}`;

    // Perbarui hidden input
    document.getElementById('subtotal_hidden').value = subtotal.toFixed(2); // nilai numerik
    document.getElementById('tax_hidden').value = tax.toFixed(2); // nilai numerik
    document.getElementById('total_hidden').value = total.toFixed(2); // nilai numerik

    // Update order date dengan waktu sekarang
    const now = new Date();
    const formattedDate = now.toISOString().split('T')[0];
    document.getElementById('order_date').value = formattedDate;

 
}


    function increaseQuantity(index) {
        orderItems[index].quantity += 1;
        subtotal += orderItems[index].price;
        updateOrderSummary();
    }

    function decreaseQuantity(index) {
        if (orderItems[index].quantity > 1) {
            orderItems[index].quantity -= 1;
            subtotal -= orderItems[index].price;
        } else {
            subtotal -= orderItems[index].price;
            orderItems.splice(index, 1);
        }
        updateOrderSummary();
    }
