function validateForm(event) {
    const senha = document.getElementById("senha").value;
    const confirmarSenha = document.getElementById("confirmar_senha").value;

    let erros = [];

    if (senha.length !== 8) {
        erros.push("A senha deve ter exatamente 8 caracteres.");
    }
    if (!/[A-Z]/.test(senha)) {
        erros.push("A senha deve conter ao menos uma letra maiúscula.");
    }
    if (!/[a-z]/.test(senha)) {
        erros.push("A senha deve conter ao menos uma letra minúscula.");
    }
    if (!/[0-9]/.test(senha)) {
        erros.push("A senha deve conter ao menos um número.");
    }
    if (!/[!@#$%&]/.test(senha)) {
        erros.push("A senha deve conter ao menos um caractere especial: ! @ # $ % &");
    }
    if (senha !== confirmarSenha) {
        erros.push("As senhas não coincidem.");
    }

    if (erros.length > 0) {
        alert(erros.join("\n"));
        event.preventDefault();
        return false;
    }

    return true;
}
