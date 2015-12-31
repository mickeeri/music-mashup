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
		        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		        <meta name="viewport" content="width=device-width, initial-scale=1">
		        <link href="css/style.css" rel="stylesheet">
		        <title>Top lists</title>
		  	</head>
	      	<body>
				<div class="container">
					<h1>My Record Collection</h1>
					' . $nv->getNavigationBar() . '
					<div class="content">
					' . $nv->getHeaderMessage() . '
		            ' . $view->response() . '
		            </div>
		        </div>
	       	</body>
	    </html>
	  	';
    }
}