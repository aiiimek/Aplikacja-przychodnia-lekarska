console.log("chuj");

var Dashboard = {
  filterDoctors: function () {
    const specid = document.getElementById("selectSpecialisation").value;
    const doctorSelect = document.getElementById("selectDoctor");
    doctorSelect.innerHTML = "<option>Ładowanie...</option>";
    
    //wysyłam getem zamiast postem (chociaż to nieładnie ;) [get jest domyślną funkcja fetcha]
    fetch('/Aplikacja-przychodnia-lekarska/sites/dashboard/getDoctors.php?spec=' + encodeURIComponent(specid))
      .then((resp) => resp.json())
      .then((data) => {
        doctorSelect.innerHTML = "";

        if (!data || data.length === 0) {
          const opt = document.createElement("option");
          opt.value = "";
          opt.textContent = "Brak lekarzy";
          doctorSelect.appendChild(opt);
          return;
        }

        data.forEach((d) => {
          const opt = document.createElement("option");
          opt.value = d.id;
          opt.textContent = d.name;
          doctorSelect.appendChild(opt);
        });
      })
      .catch(() => {
        doctorSelect.innerHTML = "<option>Błąd pobierania</option>";
      });

    console.log(specid);
  },
};
