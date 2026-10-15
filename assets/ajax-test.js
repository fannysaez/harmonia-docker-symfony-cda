console.log('bonjour!!');

async function testAjax() {
    const response = await fetch('/ajax/test');
    const data = await response.json();
    console.log(data);
}

testAjax();

let btn = document.querySelector('#btn-ajax-2');
if (btn !== null) {
    btn.addEventListener('click', () => {
        testAjax2();
    });
}

async function testAjax2() {
    const response = await fetch('/ajax/test_2');
    const data = await response.json();
    console.log(data);
}

async function checkGenre() {
    const response = await fetch('/ajax/count_genre');
    const data = await response.json();
    return data.nb;
}

const nbGenreInitial = await checkGenre();

setInterval(async () => {
    const nbGenreActuel = await checkGenre();
    console.log('actuel:', nbGenreActuel, '/ initial:', nbGenreInitial);
    if (nbGenreActuel > nbGenreInitial) {
        alert('Nouveau genre ajouté !');
    }
}, 5000);

let btnPokemon = document.querySelector('#btn-pokemon');
if (btnPokemon !== null) {
    btnPokemon.addEventListener('click', async () => {
        const response = await fetch('/ajax/check_pokemon');
        const data = await response.json();
        if (data.exists) {
            alert('Le genre Pokemon existe !');
        }
    });
}