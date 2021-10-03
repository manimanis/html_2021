const btns = Array.prototype.slice.apply(document.querySelectorAll('#cont input'));
const display = document.querySelector('#scr');

let firstValue = null;
let accumulator = [];
let operation = null;

const doOperation = (v1, v2, op) => {
  switch (op) {
    case '+': return v1 + v2;
    case '-': return v1 - v2;
    case '*': return v1 * v2;
    case '/': return v1 / v2;
  }
};

const countDigits = (value) => value.split('').reduce((p, c) => p + ((c >= '0' && c <= '9') ? 1 : 0), 0);

const addDigit = (digit) => {
  if (accumulator.length == 0) {
    if (digit != '0') {
      accumulator.push(digit);
    }
  } else {
    const cptDig = accumulator.reduce((p, c) => p + ((c >= '0' && c <= '9') ? 1 : 0), 0);
    if (digit >= '0' && digit <='9' && cptDig < 8) {
      accumulator.push(digit);
    } else if (digit == '.' && !accumulator.includes(digit)) {
      accumulator.push(digit);
    }
  }
  display.value = accumulator.join('');
};

const removeDigit = () => {
  if (accumulator.length > 0) {
    accumulator.pop();
    display.value = accumulator.join('');
  }
};

const addOperation = (op) => {
  const displayValue = (accumulator.length > 0) ? Number(accumulator.join('')) : firstValue;
  if (operation == null) {
    operation = op;
    firstValue = displayValue;
  } else {
    firstValue = doOperation(firstValue, displayValue, operation);
    operation = op;
  }
  display.value = firstValue;
  accumulator = [];
};

btns.forEach(button => button.addEventListener('click', (ev) => {
  const keyValue = ev.target.value;
  if ((keyValue >= '0' && keyValue <= '9') || keyValue == '.') {
    addDigit(keyValue);
  } else if (keyValue == '←') {
    removeDigit();
  } else if ("+-*/".indexOf(keyValue) != -1) {
    addOperation(keyValue);
  } else if (keyValue == '=') {
    addOperation('=');
    operation = null;
  } else if (keyValue == 'C') {
    display.value = '';
    operation = null;
    accumulator = [];
    firstValue = null;
  } else {
    console.log(keyValue);
  }
}));

document.addEventListener('keydown', (evt) => {
  let keyCode = evt.key;
  if (evt.keyCode == 8) {
    keyCode = '←';
  } else if (evt.keyCode == 13) {
    keyCode = '=';
  } else if (evt.keyCode == 67) {
    keyCode = 'C';
  }
  console.log(evt.key, evt.keyCode);
  let button = btns.filter(btn => btn.value == keyCode);
  if (button.length > 0) {
    button[0].click();
  }
});
