/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";
console.log("poulet");
const check = document.getElementById("checkbox_filter");

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

check.addEventListener("change", () => {
  dones.forEach((done) => {
    if (check.checked == true) {
      if (done.textContent == "Oui") {
        done.parentElement.style.display = "none";
      }
    } else {
      if ((done.parentElement.style.display = "none")) {
        done.parentElement.style.display = "table-row";
      }
    }
  });
});

const search = document.getElementById("search");
search.addEventListener("input", () => {
  fetch("http://127.0.0.1:8000/todo/search", {
    method: "post",
    body: JSON.stringify({ terms: search.value }),
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (data) {
      console.log(data);

      const tbody = document.querySelector("tbody");
      tbody.remove();
      
      const newTr = document.createElement("tr");
      tbody.appendChild(newTr);
      newTr.textContent = data;
    });
});
