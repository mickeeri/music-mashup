<?php

// AlbumModel Exceptions.
class NoAlbumTitleException extends \Exception {};
class NoArtistException extends \Exception {};
class AlbumPositionException extends \Exception {};
class AlbumIDException extends \Exception {};
class SpotifyIDException extends \Exception {};
class InvalidCoverException extends \Exception {};

// WebService Exceptions.
class CurlInitException extends \Exception {};
class WebServiceEmptyResultException extends \Exception {};
class BadResponseCodeException extends \Exception {};

// Facade exceptions
class FetchAlbumListsException extends \Exception {};
class GetDataException extends \Exception {};
class DeleteListException extends \Exception {};

// DB Exceptions
class ListAlreadyExistsException extends \Exception {};

// AlbumListModel Exceptions
class AlbumListModelException extends \Exception {};