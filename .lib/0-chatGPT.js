async function openFile(format, path){
    // Assuming format is CSV
    if(format !== 'csv') {
        console.error('Unsupported file format');
        return;
    }
    
    // Load CSV file
    const response = await fetch(path);
    const data = await response.text();

    // Parse CSV data
    const rows = data.split('\n');
    const keys = rows[0].split(',').map(key => key.trim()); // Assuming first row contains keys
    const values = rows.slice(1).map(row => row.split(',').map(value => value.trim())); // Assuming rest of the rows contain values
    
    // Create a list of products with images and buttons
    const productList = document.createElement('ul');

    // Loop through each row of values
    values.forEach((row, index) => {
        const product = document.createElement('li');

        // Assuming the first value in each row is the image path
        const image = document.createElement('img');
        image.src = row[0];
        image.alt = 'Product Image';
        product.appendChild(image);

        // Assuming the rest of the values in each row are button labels
        for(let i = 1; i < row.length; i++) {
            const button = document.createElement('button');
            button.textContent = row[i];
            product.appendChild(button);
        }

        productList.appendChild(product);
    });

    // Assuming there's a container element in the HTML with id 'productListContainer'
    const container = document.getElementById('productListContainer');
    container.appendChild(productList);

    return { keys, values };
}
