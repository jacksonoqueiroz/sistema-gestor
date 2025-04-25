//---------------------------- Apagar mensagem de sucesso ------------------

function removeMensagem(){
    setTimeout(function(){ 
        var msg = document.getElementById("msg-success");
        msg.parentNode.removeChild(msg);   
    }, 5000);
}
document.onreadystatechange = () => {
    if (document.readyState === 'complete') {
      // toda vez que a página carregar, vai limpar a mensagem (se houver) 
      // após 5 segundos
        removeMensagem(); 
    }
};

async function apagarRegistro(id){

    var confimar = confirm("Tem certeza que deseja EXCLUIR registro?");

    if (confimar == true) {
        const dados = await fetch ('excluir-operacao?id_op=' + id_op);
    }


};

// Função para gerar o número de série
        function gerarNumeroSerie($modelo, $grupo, $ordemGrupo) {
    // Garante que todos os valores estejam no formato correto
    $modelo = strtoupper(trim($modelo)); // Modelo em maiúsculo e sem espaços extras
    $grupo = str_pad($grupo, 3,'0', STR_PAD_LEFT);   // Grupo em maiúsculo e sem espaços extras
    $ordemGrupo = str_pad($ordemGrupo, 2, '0', STR_PAD_LEFT); // Ordena o grupo com 3 dígitos, caso necessário

    // Formato do número de série
    $numeroSerie = $modelo . '-' . $grupo . '-' . $ordemGrupo;

    return $numeroSerie;
}