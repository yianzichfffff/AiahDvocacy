const studentForm = document.getElementById("studentForm");
const studentsTable = document.getElementById("studentsTable");
const loadStudentsBtn = document.getElementById("loadStudents");

loadStudentsBtn.addEventListener("click", () => loadStudents());
window.addEventListener("DOMContentLoaded", () => loadStudents());

async function loadStudents() {
  try {
    const res = await fetch("Student/getStudents.php");
    const data = await res.json();

    studentsTable.innerHTML = "";

    data.forEach(stud => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${stud.stud_id}</td>
        <td>${stud.name}</td>
        <td>${stud.program_id}</td>
        <td>${stud.allowance}</td>
        <td></td>
      `;

      const actionsCell = row.querySelector("td:last-child");

      const editBtn = document.createElement("button");
      editBtn.textContent = "Edit";
      editBtn.addEventListener("click", () => populateStudentForm(stud));

      const deleteBtn = document.createElement("button");
      deleteBtn.textContent = "Delete";
      deleteBtn.addEventListener("click", () => deleteStudent(stud.stud_id));

      actionsCell.appendChild(editBtn);
      actionsCell.appendChild(deleteBtn);

      studentsTable.appendChild(row);
    });
  } catch (err) {
    console.error(err);
    alert("Failed to load students");
  }
}

function populateStudentForm(stud) {
  studentForm.stud_id.value = stud.stud_id;
  studentForm.name.value = stud.name;
  studentForm.program_id.value = stud.program_id;
  studentForm.allowance.value = stud.allowance;
}

studentForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const stud_id = studentForm.stud_id.value;
  const name = studentForm.name.value.trim();
  const program_id = studentForm.program_id.value.trim();
  const allowance = studentForm.allowance.value;

  if(!name || !program_id || !allowance) {
    alert("All fields are required");
    return;
  }

  const url = stud_id ? "Student/updateStudent.php" : "Student/addStudent.php";

  try {
    const res = await fetch(url, {
      method: "POST",
      headers: {"Content-Type":"application/json"},
      body: JSON.stringify({ stud_id, name, program_id, allowance })
    });

    const msg = await res.text();
    alert(msg);
    studentForm.reset();
    loadStudents();
  } catch(err) {
    console.error(err);
    alert("Failed to save student");
  }
});

async function deleteStudent(stud_id) {
  if(!confirm("Delete this student?")) return;

  try {
    const res = await fetch("Student/deleteStudent.php", {
      method: "POST",
      headers: {"Content-Type":"application/json"},
      body: JSON.stringify({ stud_id })
    });

    const msg = await res.text();
    alert(msg);
    loadStudents();
  } catch(err) {
    console.error(err);
    alert("Failed to delete student");
  }
}
