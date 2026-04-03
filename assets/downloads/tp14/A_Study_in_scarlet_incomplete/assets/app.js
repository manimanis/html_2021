const dots = [...document.querySelectorAll(".progress-dots .dot")];
dots.forEach(dot => dot.addEventListener("click", (event) => {
  const dot = event.target;
  const p = dots.indexOf(dot);
  updateProgressLine(p);
}));

function updateProgressLine(p) {
  const pl = document.querySelector(".progress-line");
  pl.style = `--progress: ${(p + 1) * 100 / dots.length}%;`;
  dots.forEach(dot => {
    dot.classList.remove('active');
  });
  dots[p].classList.add("active");
}

window.addEventListener('scroll', () => {
  const scPercent = window.scrollY * 100 / document.body.scrollHeight;
  const curPos = parseInt(scPercent / 8.33333333);
  updateProgressLine(curPos);
});

updateProgressLine(0);