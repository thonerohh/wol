// load and move content
async function loadAndMoveContent(url, place){
  let response = await fetch(url);
  let text = await response.text();
  place.innerHTML += text;
}
// popup for #TF fullscreen-image.html pattern
function closePopUp(e){
  e.classList.remove('active');
}
function openPopUp(e){
  document.getElementById('TF').classList.add('active');
  document.getElementById('TF').querySelector('div>img').src = e.src;
  document.getElementById('TF').querySelector('p').innerText = e.querySelector('p').innerText;
}