console.log("chuj");

var Dashboard = {
  alert: function (span, message, type = "danger") {
    if (!span) return;
    span.innerHTML = `<div class="alert alert-${type}" role="alert">${message}</div>`;
  },
  filterDoctors: function () {
    const specid = document.getElementById("selectSpecialisation").value;
    const doctorSelect = document.getElementById("selectDoctor");
    doctorSelect.innerHTML = "<option>Ładowanie...</option>";

    //wysyłam getem zamiast postem (chociaż to nieładnie ;) [get jest domyślną funkcja fetcha]
    fetch(
      "/Aplikacja-przychodnia-lekarska/sites/dashboard/getDoctors.php?spec=" +
        encodeURIComponent(specid)
    )
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

  setVisit: function () {
    const alertBox = document.getElementById("alertBox-setVisitModal");

    const spec = document.getElementById("selectSpecialisation").value;
    const doctorid = document.getElementById("selectDoctor").value;
    const visitDate = document.getElementById("visitDate").value;
    const visitDesc = document.getElementById("notes").value;

    // ZBIERAMY POLA
    const body = new URLSearchParams();
    body.append("spec", spec);
    body.append("doctorid", doctorid);
    body.append("visitDate", visitDate);
    body.append("visitDesc", visitDesc);

    fetch("/Aplikacja-przychodnia-lekarska/sites/dashboard/setVisit.php", {
      method: "POST",
      body: body,
    })
      .then((res) => {
        if (!res.ok) throw new Error("HTTP " + res.status);
        return res.json();
      })
      .then((result) => {
        if (result.code === 1) {
          Dashboard.alert(alertBox, result.txt, "success");
        } else {
          Dashboard.alert(
            alertBox,
            result.txt || "Błąd podczas umawiania wizyty"
          );
        }
      })
      .catch((err) => {
        Dashboard.alert(alertBox, "Błąd: " + err.message);
        console.error("Fetch error:", err);
      });
  },
  visitIdToCancel: null,

  prepareCancel(id) {
    this.visitIdToCancel = id;
  },

  async confirmCancel() {
    if (!this.visitIdToCancel) return;

    try {
      const response = await fetch("/Aplikacja-przychodnia-lekarska/sites/dashboard/cancelVisit.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ visitId: this.visitIdToCancel }),
      });

      const result = await response.json();

      if (result.success) {
        // tu odśwież tabelę albo stronę
        location.reload();
      } else {
        alert("Operacja nie powiodła się.");
      }
    } catch (err) {
      alert("Błąd połączenia.");
    }
  },

  initCustomDataTable: function (tableId, customControlsId, pageLength = 5) {
    let table = $(`#${tableId}`).DataTable({
      destroy: true,
      language: {
        search: "Szukaj:",
        info: "Wyświetlono _START_ do _END_ z _TOTAL_ rekordów",
        infoEmpty: "Brak rekordów",
        infoFiltered: "(filtrowano z _MAX_ wszystkich rekordów)",
        paginate: {
          first: "Pierwsza",
          last: "Ostatnia",
          next: ">>",
          previous: "<<",
        },
        zeroRecords: "Brak wyników",
      },
      pageLength: pageLength,
      lengthChange: false,
      order: [],
      initComplete: function () {
        const filter = $(`#${tableId}_filter`);
        const paginate = $(`#${tableId}_paginate`);

        paginate.css({ float: "right", margin: 0 });
        filter.find("label").css({
          display: "inline-flex",
          alignItems: "center",
          gap: "0.5rem",
          margin: 0,
        });

        // przeniesienie do customControls
        $(`#${customControlsId}`).append(filter);
      },
    });
  },
};

Dashboard.initCustomDataTable("dataTableVisits", "customControls");
Dashboard.initCustomDataTable("dataTableRec", "customControlsRec");

// tu do tabel
