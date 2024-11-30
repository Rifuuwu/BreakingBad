const targetSequence = "heisenberg";


let userInput = "";


document.addEventListener("keydown", function (event) {
    
    if (event.key.length === 1 && /^[a-zA-Z]$/.test(event.key)) {
        userInput += event.key.toLowerCase(); // Simpan huruf kecil saja
    }

    
    if (userInput.length > targetSequence.length) {
        userInput = userInput.slice(-targetSequence.length);
    }

    
    if (userInput === targetSequence) {
        alert("You're Goddamn Right!! Redirecting to Walter White's lab...");
        window.location.href = "admin/walterwhite.php";
    }
});
