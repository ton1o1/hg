<?php

function build_allCities( $cities )
{
	$allCities_html ='';

	foreach ($cities as $city) 
	{
		$allCities_html .= sprintf(
			'<p>%1$s</p>',
			$city['city']
		);
	}
	return $allCities_html;
}

function build_allFlats( $flats )
{
	$allFlats_html ='';
	
	foreach ($flats as $flat) 
	{
		$allFlats_html .=sprintf(
			'<section>
				<div>
					<a href="booking.php">
						<p>Appartement de %1$s</p>
						<p>%1$s</p>
						<p>%2$s</p>
						<p>%3$s</p>
					</a>
				</div>
				<div>
					<img src="pictures/%4$s/%4$s.jpg" alt="">
				</div>
			</section>',
			$flat['firstname'],
			$flat['lastname'],
			$flat['address'],
			$flat['id']
		);
	}
	return $allFlats_html;
}