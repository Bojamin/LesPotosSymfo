/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

const targetDiv = document.getElementById('comments-section');
const btn = document.getElementById('toggle');

btn.addEventListener('click', displayCommentsSection, false);

function displayCommentsSection() {
  targetDiv.style.display === 'none' || !targetDiv.style.cssText
    ? (targetDiv.style.display = 'block')
    : (targetDiv.style.display = 'none');
}
