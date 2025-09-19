const programForm = document.getElementById("programForm");
const programsTable = document.getElementById("programsTable");
const loadProgramsBtn = document.getElementById("loadPrograms");

loadProgramsBtn.addEventListener("click", loadPrograms);
window.addEventListener("DOMContentLoaded", loadPrograms);

function loadPrograms() {
  fetch("Programs/getPrograms.php")
    .then(res => res.json())
    .then(data => {
      programsTable.innerHTML = "";

      data.forEach(prog => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${prog.program_id}</td>
          <td>${prog.program_name}</td>
          <td>${prog.ins_id}</td>
          <td></td>
        `;
        const actionsCell = row.querySelector("td:last-child");

        const editBtn = document.createElement("button");
        editBtn.textContent = "Edit";
        editBtn.addEventListener("click", () => populateProgramForm(prog));

        const deleteBtn = document.createElement("button");
        deleteBtn.textContent = "Delete";
        deleteBtn.addEventListener("click", () => deleteProgram(prog.program_id));

        actionsCell.appendChild(editBtn);
        actionsCell.appendChild(deleteBtn);

        programsTable.appendChild(row);
      });
    })
    .catch(err => {
      console.error("Error loading programs:", err);
      alert("Failed to load programs");
    });
}

function populateProgramForm(prog) {
  programForm.program_id.value = prog.program_id;
  programForm.program_name.value = prog.program_name;
  programForm.ins_id.value = prog.ins_id;
}

programForm.addEventListener("submit", function(e) {
  e.preventDefault();

  const program_id = programForm.program_id.value;
  const program_name = programForm.program_name.value.trim();
  const ins_id = programForm.ins_id.value.trim();

  if (!program_name || !ins_id) {
    alert("All fields are required");
    return;
  }

  const url = program_id ? "Programs/updateProgram.php" : "Programs/addProgram.php";

  fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ program_id, program_name, ins_id })
  })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      programForm.reset();
      loadPrograms();
    })
    .catch(err => {
      console.error("Error saving program:", err);
      alert("Failed to save program");
    });
});

function deleteProgram(program_id) {
  if (!confirm("Delete this program?")) return;

  fetch("Programs/deleteProgram.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ program_id })
  })
    .then(res => res.text())
    .then(msg => {
      alert(msg);
      loadPrograms();
    })
    .catch(err => {
      console.error("Error deleting program:", err);
      alert("Failed to delete program");
    });
}
