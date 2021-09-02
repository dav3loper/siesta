export default class MovieRepository {

    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    getUrlFor(id) {
        return `${id}/trailer`;
    }

    updateTrailerMovie(id, trailerId) {
        let url = this.getUrlFor(id);
        return fetch(url, {
            method: 'PUT',
            body: JSON.stringify({trailer_id: trailerId}),
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken}
        })
    }

    updateAliasMovie(id, alias) {
        let url = `${id}/alias`;
        return fetch(url, {
            method: 'PUT',
            body: JSON.stringify({alias: alias}),
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken}
        })
    }

}
