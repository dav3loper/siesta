export default class ChangeAliasComponent {
    /**
     * @param {MovieRepository} movieRepository
     */
    constructor(movieRepository) {
        this.updateAliasButtonComponent = document.getElementById('updateAliasMovie');
        this.updateAliasButtonComponent.addEventListener('click', () => this.execute());
        this.updateAliasInputComponent = document.getElementById('updateAliasMovieInput');
        this.infoComponent = document.getElementById('infoForUpdateAliasMovie');
        this.movieId = this.updateAliasButtonComponent.dataset.movieid;
        this.movieRepository = movieRepository;
    }

    execute() {
        const alias = this._getAlias();

        this._updateAlias(alias)
    }

    _getAlias() {
        return this.updateAliasInputComponent.value;
    }

    async _updateAlias(alias) {
        this.movieRepository.updateAliasMovie(this.movieId, alias)
            .then(response => response.json())
            .then(result => this.infoComponent.innerText = result.status)
            .catch(error => this.infoComponent.innerText = error);
    }
}
