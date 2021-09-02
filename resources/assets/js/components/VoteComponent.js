const STATUS_ITERATION = ['check', 'bulb', 'empty'];
export default class VoteComponent {
    constructor() {
        this.state = 0;
        this.spanElement = document.querySelector('.script__vote');
        this.spanElement.addEventListener('click', () => this.handleClick());
    }

    nextIcon() {
        const index = STATUS_ITERATION.findIndex((element) => element === this.state);
        let nextItem = index === STATUS_ITERATION.length - 1 ? 0 : index + 1;
        return STATUS_ITERATION[nextItem];
    }
    currentIcon(){
        return STATUS_ITERATION[this.state];
    }

    handleClick() {
        this.spanElement.classList.remove(this.currentIcon());
        this.spanElement.classList.add(this.nextIcon());

    }
}
