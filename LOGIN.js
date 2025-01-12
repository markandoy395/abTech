// Select elements
const left = document.getElementById("left");
const right = document.getElementById("right");
const signUpBtn = document.getElementById("signUpBtn");
const loginForm = document.getElementById("loginForm");

// Add event listener to Sign Up button
signUpBtn.addEventListener("click", () => {
    // Remove the login form from the right section
    loginForm.remove();

    // Move the Sign Up button to the right section
    right.appendChild(signUpBtn);

    // Swap class names for visual effect
    left.classList.toggle("right");
    right.classList.toggle("left");
});
