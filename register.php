<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>CRS Schedule Selector</title>
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
   <style>
       .schedule-item.selected {
           background-color: black; 
           color: white; 
           cursor: pointer;
       }
       .added-subjects {
           margin-top: 20px;
       }
       .added-subjects h2 {
           margin-bottom: 10px;
       }
       .added-subjects h2 {
           font-size: 14px;
           font-family: Arial, sans-serif;
           font-weight: lighter;
           text-transform: uppercase;
           color: #1E1E1E;
           opacity: 0.5;
       }
       .add-sub {
           min-width: 23%;
       }
       .delete-sub {
           width: 40%;
           margin-top: 10px;
           margin-bottom: 10px;
           margin-left: 5px;
           border: 1px solid #ccc;
           border-radius: 10px;
           background: #F8FAFF;
           box-shadow: 0px 2px 1px 0px rgba(0, 0, 0, 0.10);
           padding: 5px 10px;
           cursor: pointer;
       }
       .schedule-item {
           background-color: #dde3f3;
           margin: 5px 0;
           padding: 10px;
           border-radius: 5px;
           display: flex;
           justify-content: space-between;
           align-items: center;
       }

       .subject-label {
        margin-bottom: 10px;
       }



   </style>
   <link rel="stylesheet" href="styles.css">
</head>
<body>
   <div>
       <main>
           <div class="select-subjects">
            <div class="subject-label">
           <label for="subjectDropdown">Select Subjects</label> </div>
               <select id="subjectDropdown" style="width: 100%;">
                   <option value=""> Select a Subject </option>
                   <option value="CMSC124">CMSC 124 - Design and Implementation of Programming Languages</option>
                   <option value="CMSC124 LAB">CMSC 124 LAB - Design and Implementation of Programming Languages</option>
                   <option value="CMSC128">CMSC 128 - Software Engineering 1</option>
                   <option value="CMSC128 LAB">CMSC 128 LAB - Software Engineering 1</option>
                   <option value="CMSC131">CMSC 131 - Introduction to Computer Organization & Machine Level Programming</option>
                   <option value="CMSC131 LAB">CMSC 131 LAB - Introduction to Computer Organization & Machine Level Programming</option>
                   <option value="CMSC141">CMSC 141 - Automata & Language Theory</option>
                   <option value="CMSC134">CMSC 134 - Human-Computer Interaction</option>
                   <option value="STAT105">STAT 105 - Introduction to Statistical Analysis</option>
                   <option value="STAT105 LAB">STAT 105 LAB - Introduction to Statistical Analysis</option>
               </select>
               <button class="plan" onclick="window.location.href = 'Generated Schedule.html'">Plan Schedule</button>

               <div id="enlistedSubjects">
                   <?php include 'view_enlisted.php'; ?>
               </div>
           </div>

           <div class="enlist-subjects">
               <div id="sched">
                   <h2>Subject Schedules</h2>
                   <div id="scheduleBox"></div>
                   <button class="add-sub" onclick="addSubject()">Add Subject</button>
               </div>
           </div>
       </main>
   </div>

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
   <script>

       function displaySchedule() {
           const subjectDropdown = document.getElementById("subjectDropdown");
           const selectedSubject = subjectDropdown.value;

           if (selectedSubject) {
               fetch('get_schedules.php?subject=' + encodeURIComponent(selectedSubject))
                   .then(response => response.json())
                   .then(data => {
                       const scheduleBox = document.getElementById("scheduleBox");
                       scheduleBox.innerHTML = ""; // Clear previous schedule elements
                       data.forEach(schedule => {
                           const scheduleElement = document.createElement("div");
                           scheduleElement.textContent = schedule;
                           scheduleElement.className = 'schedule-item'; // Add the class schedule-item
                           scheduleElement.addEventListener("click", function() {
                               if (scheduleElement.classList.contains("selected")) {
                                   scheduleElement.classList.remove("selected");
                               } else {
                                   scheduleElement.classList.add("selected");
                               }
                           });
                           scheduleBox.appendChild(scheduleElement); 
                       });
                   })
                   .catch(error => console.error('Error:', error));
           }
       }

       function addSubject() {
           const selectedSubject = document.getElementById("subjectDropdown").value;
           if (selectedSubject) {
               fetch('add_subject.php?subject=' + encodeURIComponent(selectedSubject))
                   .then(response => response.text())
                   .then(result => {
                       console.log(result); 
                       alert(result);
                   })
                   .catch(error => console.error('Error:', error));
           } else {
               alert("Please select a subject.");
           }
       }

       function deleteEnlistedSubject(subject) {
           fetch('delete_enlisted.php?subject=' + encodeURIComponent(subject))
               .then(response => response.text())
               .then(result => {
                   console.log(result);
                   const subjectElement = document.getElementById('subject-' + subject);
                   if (subjectElement) {
                       subjectElement.remove();
                   }
               })
               .catch(error => console.error('Error:', error));
       }

       $(document).ready(function() {
           $('#subjectDropdown').select2({
               placeholder: "Select a Subject",
               allowClear: true
           });

           $('#subjectDropdown').on('change', function() {
               displaySchedule();
           });
       });
   </script>
</body>
</html>