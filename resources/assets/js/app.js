/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import ChangeTrailerComponent from "./components/ChangeTrailerComponent";
import MovieRepository from "./infrastructure/MovieRepository";
import ChangeAliasComponent from "./components/ChangeAliasComponent";

require('./bootstrap');

//TODO: sacar a factorias
const changeTrailerComponent = new ChangeTrailerComponent(new MovieRepository());
const changeAliasComponent = new ChangeAliasComponent(new MovieRepository());
