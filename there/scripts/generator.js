async function generateIntro(datum){
  document.getElementById('O1').innerText = datum.O1;
  document.getElementById('O2').innerText = datum.O2;
  document.getElementById('O3').innerText = datum.O3;
  document.getElementById('O4').innerText = datum.O4;
  document.getElementById('O5').innerHTML = datum.O5.map(e => `<li>${e}</li>`).join('');
  document.getElementById('O6').src = datum.O6.src;
  document.getElementById('O6').alt = datum.O6.alt;
}
async function generateTransition(datum){
  var transition = document.getElementById('transition');
  transition.querySelector('img').src = datum.src;
  transition.querySelector('span').innerText = datum.text;
}
async function generatePost(datum){
  var post = document.getElementById('post');
  for (let i = 0; i < datum.length; i++) {
    var div = document.createElement('div');
    div.classList.add('post');
    div.onclick = function(){
      openPopUp(this);
    }
    var img = document.createElement('img');
    img.src = datum[i].img.src;
    img.alt = datum[i].img.alt;
    var p = document.createElement('p');
    p.innerText = datum[i].text;
    div.appendChild(img);
    div.appendChild(p);
    post.appendChild(div);
  }
}
async function generateAbout(datum){
  const about = document.getElementById('about');
  about.querySelector('h2').innerText = datum.h2;
  about.querySelector('h2').nextElementSibling.innerText = datum.h2_span;
  about.querySelector('p').innerText = datum.p;
  var element = about.querySelector('figure');
  for (let i = 0; i < datum.figures.length; i++) {
    // insert into figure imgs and figcaptions
    // do not create new figures
    element.innerHTML += `<img src="${datum.figures[i].img_src}" alt=""><figcaption>${datum.figures[i].figcaption}</figcaption>`;
  }
}
async function generateFact(datum){
  const fact = document.getElementById('fact');
  fact.querySelector('h2').innerText = datum.h2;
  fact.querySelector('h2').nextElementSibling.innerText = datum.h2_span;
  fact.querySelector('ul').innerHTML = '';
  datum.ul.forEach(e => {
    const li = document.createElement('li');
    li.innerText = e;
    fact.querySelector('ul').appendChild(li);
  });
  fact.querySelector('.advertising>img').src = datum.advertising.src;
  fact.querySelector('.advertising>img').alt = datum.advertising.alt;
}
async function generateReview(datum){
  const review = document.getElementById('review');
  review.querySelector('h2').innerText = datum.h2;
  review.querySelector('span').innerText = datum.h2_span;
  const g = review.querySelector('.g');
  for (let i = 0; i < datum.reviews.length; i++) {
    const review = datum.reviews[i];
    const div = document.createElement('div');
    div.classList.add('review');
    g.appendChild(div);
    const img = document.createElement('img');
    img.src = review.img.src;
    img.alt = review.img.alt;
    div.appendChild(img);
    const g1 = document.createElement('div');
    g1.classList.add('g1');
    div.appendChild(g1);
    const h3 = document.createElement('h3');
    h3.innerText = review.g1.h3;
    g1.appendChild(h3);
    const span = document.createElement('span');
    span.innerText = review.g1.span.date + " / " + review.g1.span.place + " (" + review.g1.span.id + ", " + review.g1.span.link + ")";
    g1.appendChild(span);
    const p = document.createElement('p');
    p.innerText = review.g1.p;
    g1.appendChild(p);
  }
}
async function generateService(datum){
  const service = document.getElementById('service');
  service.querySelector('h2').innerText = datum.h2;
  service.querySelector('span').innerText = datum.h2_span;
  const g = service.querySelector('.g');
  for (let i = 0; i < datum.services.length; i++) {
    const service = datum.services[i];
    const div = document.createElement('div');
    div.classList.add('service');
    g.appendChild(div);
    const img = document.createElement('img');
    img.src = service.img.src;
    img.alt = service.img.alt;
    div.appendChild(img);
    const g1 = document.createElement('div');
    g1.classList.add('g1');
    div.appendChild(g1);
    const h3 = document.createElement('h3');
    h3.innerText = service.g1.h3;
    g1.appendChild(h3);
    const span = document.createElement('span');
    span.innerText = service.g1.span.date + " / " + service.g1.span.place + " (" + service.g1.span.id + ", " + service.g1.span.link + ")";
    g1.appendChild(span);
    const s = document.createElement('div');
    s.classList.add('s');
    g1.appendChild(s);
    for (let i = 0; i < service.g1.s.length; i++) {
      const a = service.g1.s[i];
      const a_ = document.createElement('a');
      a_.href = a.href;
      a_.innerText = a.text;
      if (a.main) {
        a_.classList.add('target');
      }
      s.appendChild(a_);
    }
    const p = document.createElement('p');
    p.innerText = service.g1.p;
    g1.appendChild(p);
    const ul = document.createElement('ul');
    g1.appendChild(ul);
    for (let i = 0; i < service.g1.ul.length; i++) {
      const li = document.createElement('li');
      li.innerText = service.g1.ul[i];
      ul.appendChild(li);
    }
  }
}
async function generateContact(datum){
  const contact = document.getElementById('contact');
  contact.querySelector('h2').innerText = datum.h2;
  contact.querySelector('h2').nextElementSibling.innerText = datum.h2_span;
  contact.querySelector('p').innerText = datum.p;
  contact.querySelector('ul').innerHTML = '';
  datum.ul.forEach(e => {
    const li = document.createElement('li');
    li.innerText = e;
    contact.querySelector('ul').appendChild(li);
  });
  contact.querySelector('ul').nextElementSibling.innerHTML = '<ul></ul>';
  datum.ul2.forEach(e => {
    const li = document.createElement('li');
    const a = document.createElement('a');
    a.href = e.href;
    a.innerText = e.text;
    a.classList.add(e.class);
    li.appendChild(a);
    contact.querySelector('.g > ul').appendChild(li);
  });
  const form = document.createElement('form');
  form.action = datum.form.action;
  form.method = datum.form.method;
  form.innerHTML = `
    <label for="input01">Ваш телефон</label><input type="text" id="input01" placeholder="Ваш телефон">
    <label for="input02">Ваша почта</label><input type="text" id="input02" placeholder="Ваша почта">
    <label for="input03">Ваше сообщение</label><textarea name="" id="input03" cols="30" rows="10" placeholder="Ваше сообщение"></textarea>
    <button type="submit">Отправить</button>
  `;
  contact.querySelector('.g').appendChild(form);
}
async function generateHeader(datum){
  const header = document.querySelector('header');
  header.innerHTML = '';
  // create header > div
  const div = document.createElement('div');
  // add div to header
  header.append(div);

  const brand = document.createElement('div');
  brand.classList.add('brand');
  const img = document.createElement('img');
  img.src = datum.brand.src;
  img.alt = datum.brand.span;
  const span = document.createElement('span');
  span.innerText = datum.brand.span;
  brand.append(img, span);
  div.append(brand);
  const nav = document.createElement('nav');
  datum.nav.forEach(e => {
    const a = document.createElement('a');
    a.href = e.href;
    a.innerText = e.text;
    nav.append(a);
  });
  // add to first nav>a class="target"
  nav.querySelector('a').classList.add('target');
  div.append(nav);
}
async function loadPosts(data) {
  // connect to db and get posts as json
  const requestOptions = {
    method: "POST",
    redirect: "follow"
  };

  try {
    const response = await fetch("/were/handler.php", requestOptions);
    const result = await response.text();
    const new_data = JSON.parse(result);

    console.log(new_data);
    // Assuming you want to replace data.post with new_data
    // new_data contains more than needed:
    // {
    //   "id": "1",
    //   "img": "/there/uploads/65f5a6e3d3317.png",
    //   "alt": "Post 1",
    //   "text": "Lorem Ipsum :)",
    //   "date_created": "2024-03-16 14:04:19",
    //   "date_edited": "2024-03-16",
    //   "user_agent": "PostmanRuntime/7.36.3",
    //   "ip": "89.116.228.77",
    //   "other": null
    // }

    // needed format:
    // img: {
    //   src: "/there/placeholder/image.png",
    //   alt: ""
    // },
    // text: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos, sed quisquam, repudiandae dolorem, esse omnis vitae porro magni hic ea maiores."


    data.post = new_data.map(e => {
      return {
          img: {
            src: e.img,
            alt: e.alt
          },
          text: e.text
        }
      }
    );
  } catch (error) {
    console.error(error);
  }
}
async function init(){
  // generate intro from datum
  // generate transition from datum
  // generate post from datum
  // generate about from datum
  // generate fact's from datum
  // generate review from datum
  // generate service from datum
  // generate contact from datum
  // generate header from datum
  await generateIntro(datum.intro);
  await generateTransition(datum.transition);
  await loadPosts(datum);
  await generatePost(datum.post);
  await generateAbout(datum.about);
  await generateService(datum.service);
  await generateContact(datum.contact);
  await generateHeader(datum.header);
}