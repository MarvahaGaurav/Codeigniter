<?php 

/** 
 * @SWG\Swagger(
 *   @SWG\Info(
 *      description = "Smart Guide APIs",
 *      title = "Smart Guide",
 *      version = "1"       
 *   ),
 *   schemes={"http"},  
 *   host="smartguide-dev.applaurels.com",
 *   basePath = "/api/v1",
 *   
 * @SWG\SecurityScheme(
 *   securityDefinition="basicAuth",
 *   type="basic",
 *   in="header",
 *   name="Authorization"
 * ),
 *
 *   @SWG\Tag(
 *     name="User",
 *     description="",
 *   ),
 *   security={{  "basicAuth": {""} } },
 * )  
 */
