//https://codepen.io/carsonf92/pen/xEBxwP
import VoteComponent from "./VoteComponent";

export default class ScoreComponent {
    constructor(selector) {

        this.scoreComponent = document.querySelector(selector);
        this.scoreComponent.querySelectorAll('.script__vote').forEach( element => {
            new VoteComponent('#'+element.id);
        });


    }




}
