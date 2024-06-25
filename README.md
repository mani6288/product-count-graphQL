# ABD CatalogGraphQl Module

## Overview

The `ABD_CatalogGraphQl` module extends Magento's GraphQL API to include the product count for categories. This module defines a GraphQL schema and resolver that retrieves and returns the number of products in a specific category.

## Features

- Adds a `product_count` field to the `Category` GraphQL type.
- Resolves the `product_count` field using the `ProductsCount` resolver class.
- Utilizes the Smile ElasticsuiteCatalog product collection for efficient product counting.
