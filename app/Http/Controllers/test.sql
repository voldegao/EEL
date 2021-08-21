// Coefficient QUERY

//FINAL

SELECT
	article_id,
	SUM( quantite ) AS sumVente,
	COUNT(Extract( MONTH FROM date_vente )) AS NombreVente,
	COUNT(DISTINCT Extract( MONTH FROM date_vente )) AS NombreMois,
	AVG(quantite) AS Moyenne
FROM
	commandes
WHERE date_vente BETWEEN '2021-01-01'	AND '2021-04-30'
GROUP BY
	article_id








// Pour chaque articles => nombre de mois

SELECT
	article_id,
	Count(distinct extract(MONTH FROM date_vente )) AS mois
FROM
	commandes 
GROUP BY
	article_id


// Pour chaque article => somme de vente total 

SELECT
	article_id,
	SUM( quantite ) AS sumVente
FROM
	commandes 
GROUP BY
	article_id




SELECT
	article_id,
	extract( MONTH FROM date_vente ) AS mois,
	SUM( quantite ) AS sumVente
FROM
	commandes 
GROUP BY
	article_id,
	EXTRACT( MONTH FROM date_vente )