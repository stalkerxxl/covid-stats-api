import {Controller} from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    static values = {
        sortBy: String,
        direction: String
    }

    static targets = ['name'];


    connect() {
        const cells = this.element.getElementsByTagName('th')
        for (const cell in cells) {
            console.log(cell)
        }

    }

    changeDirection() {
        //console.log(this.nameTarget);
    }
}
