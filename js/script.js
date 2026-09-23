// ============================== // LOGIN // ============================== // 
// 
const loginForm = document.getElementById("loginForm"); 
if (loginForm) { 
    loginForm.addEventListener("submit", function(event) 
    { event.preventDefault(); 
        const username = document.getElementById("username").value; 
        const password = document.getElementById("password").value; 
        const message = document.getElementById("loginMessage"); 
        // Login demonstrativo 
        if (username === "admin" && password === "1234") 
        { localStorage.setItem("loggedIn", "true");
        message.style.color = "#65df86"; message.textContent = "Login realizado com sucesso!"; 
        setTimeout(function() { 
            window.location.href = "dashboard.html";
        }, 800); 
        }else { 
            message.style.color = "#e56b6b";
            message.textContent = "Utilizador ou senha incorretos."; 
        } 
    });
} 
        // ============================== // MOSTRAR / OCULTAR SENHA // ============================== 
    const togglePassword = document.getElementById("togglePassword");

    if (togglePassword) {

        togglePassword.addEventListener("click", function () {

            const password = document.getElementById("senha");

        if (password.type === "password") {

            password.type = "text";

            togglePassword.textContent = "🙈";

        } else {

            password.type = "password";

            togglePassword.textContent = "👁";

        }

    });

}
         // ============================== // 
         // LOGOUT // ============================== 
         const logoutBtn = document.getElementById("logoutBtn"); 
         if (logoutBtn) { 
            logoutBtn.addEventListener("click", function() { 
                localStorage.removeItem("loggedIn"); 
                window.location.href = "index.html"; 
            }); 
        }