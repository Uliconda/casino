const uname = document.querySelector('#uname');
const pass = document.querySelector('#pass');
const btnContainer = document.querySelector('.btn-container');
const btn = document.querySelector('#login-btn');
const form = document.querySelector('form');
const msg = document.querySelector('#msg');

let moveCount = 0;
const maxMoves = 4;

const positions = ['shift-left', 'shift-top', 'shift-right', 'shift-bottom'];

btn.disabled = true;

function showMsg(text = "Por favor completa ambos campos") {
  msg.innerText = text;
  msg.style.color = "red";
}

function resetButtonPosition() {
  btn.classList.remove(...positions);
  btn.style.top = "0";
  btn.style.left = "0";
  moveCount = 0;
}

function shiftButton() {
  showMsg();

  const current = positions.find(pos => btn.classList.contains(pos));
  btn.classList.remove(...positions);

  if (moveCount >= maxMoves) {
    resetButtonPosition();
    return;
  }

  let next;
  do {
    next = positions[Math.floor(Math.random() * positions.length)];
  } while (next === current);

  btn.classList.add(next);
  moveCount++;
}

function checkFields() {
  if (uname.value.trim() && pass.value.trim()) {
    btn.disabled = false;
    resetButtonPosition();
    msg.innerText = "";
  } else {
    btn.disabled = true;
  }
}

// Eventos
uname.addEventListener("input", checkFields);
pass.addEventListener("input", checkFields);

btn.addEventListener("mouseover", () => {
  if (btn.disabled) {
    shiftButton();
  }
});
