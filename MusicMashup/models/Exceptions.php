<?php

// AlbumModel Exceptions.
class NoAlbumTitleException extends \Exception {};
class NoArtistException extends \Exception {};
class AlbumPositionException extends \Exception {};
class AlbumIDException extends \Exception {};
class SpotifyIDException extends \Exception {};

// WebService Exceptions.
class CurlInitException extends \Exception {};
class WebServiceEmptyResultException extends \Exception {};
class BadResponseCodeException extends \Exception {};