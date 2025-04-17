document.addEventListener("DOMContentLoaded", function () {
    function applyFilter() {
        const searchInput = document.getElementById("search-input").value.toLowerCase();
        const filterCategory = document.getElementById("filter-category").value;
        const tableRows = document.querySelectorAll(".product-table tbody tr");

        tableRows.forEach(row => {
            const cells = row.getElementsByTagName("td");
            if (cells.length > 0) {
                let showRow = false;

                // Adjusted correct column indices
                if (filterCategory == "0") { // Product Name
                    showRow = cells[0].textContent.toLowerCase().includes(searchInput);
                } else if (filterCategory == "6") { // Status
                    showRow = cells[6].textContent.toLowerCase().includes(searchInput);
                } else if (filterCategory == "4") { // Total Stocks (Quantity)
                    showRow = cells[4].textContent.toLowerCase().includes(searchInput);
                } else if (filterCategory == "5") { // Price
                    showRow = cells[5].textContent.toLowerCase().includes(searchInput);
                } else if (filterCategory == "7") { // Total Price
                    showRow = cells[7].textContent.toLowerCase().includes(searchInput);
                }

                row.style.display = showRow ? "" : "none";
            }
        });
    }

    function resetFilter() {
        document.getElementById("search-input").value = "";
        document.getElementById("filter-category").value = "0";

        const tableRows = document.querySelectorAll(".product-table tbody tr");
        tableRows.forEach(row => {
            row.style.display = "";
        });
    }

    // ✅ Attach event listeners
    document.querySelector(".btn-apply").addEventListener("click", applyFilter);
    document.querySelector(".btn-reset").addEventListener("click", resetFilter);
});

    

// JavaScript to handle tab switching and show appropriate product views
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const view = tab.dataset.view;

        document.querySelectorAll('.product-view').forEach(view => view.classList.remove('active'));
        document.querySelector(`#${view}-view`).classList.add('active');

        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        tab.classList.add('active');
    });
});

// JavaScript to switch between product list and add new product form
document.addEventListener("DOMContentLoaded", function () {
    // Show Add Product Form
    document.addEventListener("DOMContentLoaded", function () {
        const addProductBtn = document.getElementById('add-product-menu-item');
        const productsBtn = document.getElementById('products-menu-item');
        const productListSection = document.getElementById('product-list-section');
        const addProductSection = document.getElementById('add-product-section');
        const form = document.getElementById('product-form');
        const saveButton = document.getElementById('save-button');
    
        // Show Add Product form
        addProductBtn.addEventListener('click', () => {
            productListSection.style.display = 'none';
            addProductSection.style.display = 'block';
        });
    
        // Show Product Table
        productsBtn.addEventListener('click', () => {
            addProductSection.style.display = 'none';
            productListSection.style.display = 'block';
    
            // Reset form (if needed)
            if (form) {
                form.reset();
                document.getElementById('product-id').value = '';
                saveButton.textContent = 'Add Product';
            }
        });
    });
    
});



document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    const saveButton = document.getElementById("save-button");

    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Fill the form with data from the selected product
            document.getElementById("product-id").value = this.getAttribute("data-id");
            document.getElementById("product-name").value = this.getAttribute("data-name");
            document.getElementById("product-design").value = this.getAttribute("data-design");
            document.getElementById("product-color").value = this.getAttribute("data-color");
            document.getElementById("product-size").value = this.getAttribute("data-size");
            document.getElementById("product-quantity").value = this.getAttribute("data-quantity");
            document.getElementById("product-price").value = this.getAttribute("data-price");
            document.getElementById("status").value = this.getAttribute("data-status");

            // Make sure the form is visible and product list is hidden
            document.getElementById("add-product-section").style.display = "block";
            document.getElementById("product-list-section").style.display = "none";

            // Change button text to indicate editing mode
            saveButton.textContent = "Save Changes";

            // Scroll to the form smoothly
            document.getElementById("add-product-section").scrollIntoView({ behavior: "smooth" });
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    const form = document.getElementById("product-form");
    const saveButton = document.getElementById("save-button");

    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("product-id").value = this.getAttribute("data-id");
            document.getElementById("product-name").value = this.getAttribute("data-name");
            document.getElementById("product-design").value = this.getAttribute("data-design");
            document.getElementById("product-color").value = this.getAttribute("data-color");
            document.getElementById("product-size").value = this.getAttribute("data-size");
            document.getElementById("product-quantity").value = this.getAttribute("data-quantity");
            document.getElementById("product-price").value = this.getAttribute("data-price");
            document.getElementById("status").value = this.getAttribute("data-status");

            // Change button text to "Save Changes"
            saveButton.textContent = "Save Changes";
        });
    });
});
