/***********************|
|    GLOBAL VARIABLE    |
|***********************/
var formstat = false;

/***********************|
|    GLOBAL FUNCTION    |
|***********************/
function popup_message(text) {
    document.getElementById("popup_message").innerText = text;
    document.getElementById("popup").style.display = "flex";
}

function popup_page_stay(text) {
    document.getElementById("popup_message_stay").innerText = text;
    document.getElementById("popup_page_stay").style.display = "flex";
}

function popup_form(){
    document.getElementById("popup_form").style.display = "flex";
}

function confirmJoin(eventId) {
    var popup = document.getElementById('join_confirmation_popup');
    document.getElementById('event_id_to_join').value = eventId;
    popup.style.display = 'flex';
}

function cancelJoin() {
    var popup = document.getElementById('join_confirmation_popup');
    popup.style.display = 'none';
}

function confirmJoinAction() {
    var eventIdToJoin = document.getElementById('event_id_to_join').value;
    window.location.href = 'join_event.php?event_id=' + eventIdToJoin;
}



function confirmUnjoin(eventId) {
    var popup = document.getElementById('unjoin_confirmation_popup');
    document.getElementById('event_id_to_unjoin').value = eventId;
    popup.style.display = 'flex';
}

function cancelUnjoin() {
    var popup = document.getElementById('unjoin_confirmation_popup');
    popup.style.display = 'none';
}

function confirmUnjoinAction() {
    var eventIdToUnjoin = document.getElementById('event_id_to_unjoin').value;
    window.location.href = 'unjoin_event.php?event_id=' + eventIdToUnjoin;
}

function recordAttendance(eventId) {
    var passwordPopup = document.getElementById('attendance_password_popup');
    passwordPopup.style.display = 'flex';
    document.getElementById('event_id_for_attendance').value = eventId;
}

function cancelAttendancePassword() {
    // Get the attendance password pop-up element
    var popup = document.getElementById('attendance_password_popup');

    // Clear the input field
    document.getElementById('event_password').value = "";

    // Hide the attendance password pop-up
    popup.style.display = 'none';
}

function submitAttendancePassword() {
    var eventIdForAttendance = document.getElementById('event_id_for_attendance');
    var eventPassword = document.getElementById('event_password');
    if (eventIdForAttendance && eventPassword) {
        window.location.href = 'update_attendance.php?event_id=' + eventIdForAttendance.value + '&event_pwd=' + eventPassword.value;
    }
}

function closeAttendanceSuccessPopup() {
    document.getElementById('attendance_success_popup').style.display = 'none';
}

function closeWrongPasswordPopup() {
    document.getElementById('wrong_password_popup').style.display = 'none';
}

function open_change_pass(){
    document.getElementById("popup-form").style.display = "flex";
}

function showFeedbackPopup(eventId, eventName) {
    document.getElementById('event_id_to_feedback').value = eventId;

    document.getElementById('event_name_in_feedback').innerText = eventName;

    document.getElementById('feedback_popup').style.display = 'flex';
}

function cancelFeedback() {
    // Close the feedback popup and reset the hidden input field
    document.getElementById('feedback_popup').style.display = 'none';
    
    // Reset the hidden input field value
    document.getElementById('event_id_to_feedback').value = '';
}

/**********************|
|    AUTO OPEN POPUP   |
|**********************/
function auto_open_popup(id){
    if(formstat == false){
        document.getElementById(id).style.display = "flex";
    }
    else{
        document.getElementById(id).style.display = "none";
    }
}

function auto_popup_message(text) {
    formstat = true;
    document.getElementById("popup_message").innerText = text;
    document.getElementById("popup").style.display = "flex";
}

/***********************|
|       RESET FORM      |
|***********************/
function reset_form(text) {
    var form = document.getElementById(text);
    form.reset();
}