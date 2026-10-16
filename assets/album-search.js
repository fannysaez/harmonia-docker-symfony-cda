let input = document.querySelector('#search-album');
let resultsDiv = document.querySelector('#search-results');

if (input !== null) {
    input.addEventListener('input', () => {
        searchAlbum(input.value);
    });
}

async function searchAlbum(search) {
    const response = await fetch('/album/search?search=' + search);
    const data = await response.json();
    console.log(data);

    resultsDiv.innerHTML = '';

    data.albums.forEach(album => {
        resultsDiv.innerHTML += `
            <a href="/album/${album.id}" class="card mb-2 p-2 text-decoration-none text-dark d-block">
                <strong>${album.title}</strong> — ${album.artist ? album.artist.stageName : 'Artiste inconnu'}
            </a>
        `;
    });
}