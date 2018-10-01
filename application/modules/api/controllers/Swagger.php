<?php 

/** 
 * @SWG\Swagger(
 * @SWG\Info(
 *      description = "Smart Guide APIs, valid lanuage code's
 *          en : English
 *          da : Danish
 *          nb : Norwegian
 *          sv : Swedish
 *          fi : Finnish
 *          fr : French
 *          nl : Dutch
 *          de : German",
 *      title = "Smart Guide",
 *      version = "1"       
 *   ),
 *   schemes={"http"},  
 *   host="smartguide-staging.applaurels.com",
 *   basePath = "/api/v1",
 *   
 * @SWG\SecurityScheme(
 *   securityDefinition="basicAuth",
 *   type="basic",
 *   in="header",
 *   name="Authorization"
 * ),
 *
 * @SWG\Tag(
 *     name="User",
 *     description="",
 *   ),
 *   security={{  "basicAuth": {""} } },
 * )  
 */
