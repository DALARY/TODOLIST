/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";

const dones = document.querySelectorAll(".done");
dones.forEach((done) => {
  done.addEventListener("click", () => {
    fetch(
      "http://127.0.0.1:8000/todo/" + done.parentElement.id + "/updateDone",
      {
        method: "post",
        body: JSON.stringify({ id: done.parentElement.id }),
      }
    )
      .then(function () {
        return "Accès valide";
      })
      .then(function (json) {
        console.log(json);
        if (done.textContent == "Oui") {
          done.textContent = "Non";
        } else {
          done.textContent = "Oui";
        }
      });
  });
});

const check = document.getElementById("checkbox_filter");
check.addEventListener("change", () => {
  if (check.checked) {
    fetch("http://127.0.0.1:8000/todo/filter", {
      method: "post",
      body: JSON.stringify({ terms: search.value }),
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (data) {
        const tbody = document.querySelector("tbody");
        tbody.remove();

        const table = document.querySelector("table");
        const newTbody = document.createElement("tbody");

        table.appendChild(newTbody);
        data
          .filter((item) => !item.done)
          .forEach((item) => {
            const newTr = document.createElement("tr");

            // Création des cellules pour chaque propriété de l'objet item
            const idTd = document.createElement("td");
            idTd.textContent = item.id;
            newTr.appendChild(idTd);

            const nameTd = document.createElement("td");
            nameTd.textContent = item.name;
            newTr.appendChild(nameTd);

            const doneTd = document.createElement("td");
            doneTd.textContent = item.done ? "Oui" : "Non";
            newTr.appendChild(doneTd);

            const descriptionTd = document.createElement("td");
            descriptionTd.textContent = item.description;
            newTr.appendChild(descriptionTd);

            const priorityTd = document.createElement("td");
            priorityTd.textContent = item.priority.level;
            newTr.appendChild(priorityTd);

            const ButtonTd = document.createElement("td");

            const showLink = document.createElement("a");
            showLink.href = item.id;
            showLink.textContent = "show";

            const editLink = document.createElement("a");
            editLink.href = item.id + "/edit";
            editLink.textContent = "edit";

            ButtonTd.appendChild(showLink);
            ButtonTd.appendChild(document.createTextNode(" "));
            ButtonTd.appendChild(editLink);
            newTr.appendChild(ButtonTd);

            newTbody.appendChild(newTr);
          });
      });
  } else {
    window.location.href = "/todo";
  }
});

const search = document.getElementById("search");
search.addEventListener("input", () => {
  if (search.value.trim() !== "") {
  fetch("http://127.0.0.1:8000/todo/search", {
    method: "post",
    body: JSON.stringify({ terms: search.value }),
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      const tbody = document.querySelector("tbody");
      tbody.remove();

      const table = document.querySelector("table");
      const newTbody = document.createElement("tbody");

      table.appendChild(newTbody);
      data.forEach((item) => {
        const newTr = document.createElement("tr");

        // Création des cellules pour chaque propriété de l'objet item
        const idTd = document.createElement("td");
        idTd.textContent = item.id;
        newTr.appendChild(idTd);

        const nameTd = document.createElement("td");
        nameTd.textContent = item.name;
        newTr.appendChild(nameTd);

        const doneTd = document.createElement("td");
        doneTd.textContent = item.done ? "Oui" : "Non";
        newTr.appendChild(doneTd);

        const descriptionTd = document.createElement("td");
        descriptionTd.textContent = item.description;
        newTr.appendChild(descriptionTd);

        const priorityTd = document.createElement("td");
        priorityTd.textContent = item.priority.level;
        newTr.appendChild(priorityTd);

        const ButtonTd = document.createElement("td");

        const showLink = document.createElement("a");
        showLink.href = item.id;
        showLink.textContent = "show";

        const editLink = document.createElement("a");
        editLink.href = item.id + "/edit";
        editLink.textContent = "edit";

        ButtonTd.appendChild(showLink);
        ButtonTd.appendChild(document.createTextNode(" "));
        ButtonTd.appendChild(editLink);
        newTr.appendChild(ButtonTd);

        newTbody.appendChild(newTr);
      });
    });
  } else {
    window.location.href = "/todo";
  }
});
