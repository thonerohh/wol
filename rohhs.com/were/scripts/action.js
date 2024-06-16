// load and move content
async function loadAndMoveContent(url, place){
  let response = await fetch(url);
  let text = await response.text();
  place.innerHTML += text;
}