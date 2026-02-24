fetch("http://localhost:8000/api/news")
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById("news");

    data.forEach(n => {
      const item = document.createElement("div");
      item.className = "news-item";

      const imageUrl = `http://localhost:8000/storage/${n.image}`;

      item.innerHTML = `
        <h2>${n.title}</h2>
        <img src="${imageUrl}" width="300">
        <div>${n.content}</div>
      `;

      container.appendChild(item);
    });
  });