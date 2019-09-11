export default class ChangeTrailerComponent {
    /**
     * @param {MovieRepository} movieRepository
     */
    constructor(movieRepository) {
        this.idInputComponent = document.querySelector('#newIDtrailer');
        this.movieId = this.idInputComponent.dataset.movieid;
        this.saveTrailerButtonComponent = document.querySelector('#saveNewTrailer');
        this.saveTrailerButtonComponent.addEventListener('click', () => this.execute());
        this.movieRepository = movieRepository;
        this.infoComponent = document.querySelector('#infoForNewIdTrailer');
    }

    execute() {
        try {
            this._checkTrailerValue();

            let trailerId = this.getTrailerId();

            this._updateTrailerId(trailerId);
        } catch (e) {

        }

    }

    _checkTrailerValue() {
        if (this.getTrailerId() === '') {
            throw new DOMException();
        }

    }

    getTrailerId() {
        return this.idInputComponent.value;
    }

    async _updateTrailerId(trailerId) {
        await this.movieRepository.updateTrailerMovie(this.movieId, trailerId)
            .then(response => response.json())
            .then(result => this.infoComponent.innerText = result.status)
            .catch(error => this.infoComponent.innerText = error);
    }
}