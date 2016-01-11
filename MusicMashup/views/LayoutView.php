<?php
/**
 * Created by PhpStorm.
 * User: Micke
 * Date: 2015-12-31
 * Time: 11:53
 */

namespace views;


class LayoutView
{
    public function renderLayout(\views\NavigationView $nv, $view)
    {
        echo '
		<!DOCTYPE html>
		    <html lang="en">
		      <head>
		        <meta charset="utf-8">
		        <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		        <link href="css/style.css" rel="stylesheet">
		        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
		        <title>Top lists</title>
		        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		  	</head>
	      	<body>
				<div class="container">
				    <div class="row">
				        <div class="col s12 m12 l12">
                            <header>
                                ' . $nv->getNavigationBar() . '
                            </header>
                            <main>
                                ' . $view->response() . '
                            </main>
                        </div>
                    </div>
		        </div>
                <script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
		        <script src="//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
                <script type="text/javascript" src="js/AlbumListMaker.js"></script>
	       	</body>
	    </html>
	  	';
    }
}