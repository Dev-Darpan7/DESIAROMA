function shopNow() {
    document.body.insertAdjacentHTML("beforeend", `
        <div id="luxuryPopup" class="popup">
            <div class="popup-content">
                <span onclick="closePopup()" class="close-btn">&times;</span>
                <h2>Welcome to DESIAROMA</h2>
                <img src="https://images.unsplash.com/photo-1587019158091-1a103c5dd17f?auto=format&fit=crop&w=800&q=80" alt="Luxury Perfume">
                <p>Discover timeless elegance and royal fragrances.</p> 
            </div>
        </div>
    `);
}

function addToCart(productName) {

    let imageURL = "";

    if(productName === "Royal Oud") {
        imageURL = "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=600&q=80";
    }
    else if(productName === "Midnight Musk") {
        imageURL = "https://images.unsplash.com/photo-1615634260167-c8cdede054de?auto=format&fit=crop&w=600&q=80";
    }
    else if(productName === "Floral Bliss") {
        imageURL = "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?auto=format&fit=crop&w=600&q=80";
    }

    document.body.insertAdjacentHTML("beforeend", `
        <div id="luxuryPopup" class="popup">
            <div class="popup-content">
                <span onclick="closePopup()" class="close-btn">&times;</span>
                <h2>${productName} Added to Cart</h2>
                <img src="${imageURL}" alt="${productName}">
                <p>This luxurious fragrance has been added to your collection.</p>
            </div>
        </div>
    `);
}

function closePopup() {
    document.getElementById("luxuryPopup").remove();
}+
