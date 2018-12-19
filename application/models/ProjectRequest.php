    <?php

    defined("BASEPATH") or exit("No direct script access allowed");

    require_once 'BaseModel.php';

    use DatabaseExceptions\SelectException;

    class ProjectRequest extends BaseModel
    {

        public function __construct()
        {
            $this->load->database();
            $this->tableName = "project_requests as pr";
        }



        /**
         * get project listing
         *
         * @param array $params
         * @return array
         */
        public function get($params)
        {
            $query = "";
            $this->db->select($query, false)
                ->join('project_id')
                ->from($this->tableName);

            if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
                $this->db->limit((int)$params['limit']);
            }

            if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
                $this->db->offset((int)$params['offset']);
            }

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            $query = $this->db->get();

            $result['data'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

            return $result;
        }

        public function requestInfoWithPrice($projectIds)
        {
            $this->db->select(
                "SUM(IFNULL(price_per_luminaries, 0)) as price_per_luminaries,
            SUM(IFNULL(installation_charges, 0)) as installation_charges, avg(IFNULL(discount_price, 0)) as discount_price,
            SUM(((IFNULL(installation_charges, 0) + IFNULL(price_per_luminaries, 0)) * ((100 - IFNULL(discount_price, 0))/ 100))) as discounted_price, IFNULL(pq.status, 0) as status, 
            IFNULL(additional_product_charges, 0) as additional_product_charges, IFNULL(discount, 0) as discount,
            first_name as full_name, company_name,"
            )
                ->from("project_request_installers as pri")
                ->join("ai_user as user", 'user.company_id=pri.company_id AND is_owner=2')
                ->join("company_master as cm", "cm.company_id=pri.company_id")
                ->join("project_requests as preq", "preq.id=pri.request_id")
                ->join("project_rooms as pr", "pr.project_id=preq.project_id")
                ->join("project_room_quotations as prq", "prq.project_room_id=pr.id AND prq.company_id=pri.company_id", "left")
                ->join("project_quotations as pq", "pq.request_id=preq.id AND pq.company_id=pri.company_id", "left")
                ->group_by("pri.company_id")
                ->group_by("preq.project_id")
                ->where_in("pr.project_id", $projectIds);

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            if (isset($params['where_in']) && is_array($params['where_in']) && !empty($params['where_in'])) {
                foreach ($params['where_in'] as $tableColumn => $searchValue) {
                    $this->db->where_in($tableColumn, $searchValue);
                }
            }

            if (isset($params['group_by']) && is_array($params['group_by']) && !empty($params['group_by'])) {
                foreach ($params['group_by'] as $field) {
                    $this->db->group_by($field);
                }
            }

            $query = $this->db->get();

            $result = $query->result_array();

            if (!empty($result)) {
                $this->load->helper(['utility']);
                $result = array_map(function ($quotation) {
                    $sum = $quotation['installation_charges'] + $quotation['price_per_luminaries'];
                    $quotation['discounted_price'] = sprintf('%.2f', $quotation['discounted_price']);
                    $quotation['discount_price'] = $sum>0?sprintf("%.2f", (1 - ($quotation['discounted_price'] / $sum)) * 100):0;
                    $quotation['discount_percent'] = $quotation['discount_price'];
                    $sum += $quotation['additional_product_charges'];
                    $quotation['subtotal'] = $sum;
                    $quotation['total'] = sprintf("%.2f", get_percentage($sum, $quotation['discount']));
                    return $quotation;
                }, $result);
            }

            return $result;
        }

        /**
         * Customer Request
         *
         * @param array $params
         * @return void
         */
        public function customerRequests($params)
        {
            $this->db->select(
                'SQL_CALC_FOUND_ROWS pr.id as request_id, projects.name as project_name,
                projects.address as project_address, projects.id as project_id, projects.lat as project_lat,
                projects.lng as project_lng, pr.created_at as request_created_at, projects.number as project_number,
                pr.created_at_timestamp as request_created_at_timestamp,pq.status',
                false
            )
                ->from('projects')
                ->join('project_requests as pr', 'pr.project_id=projects.id')
                ->join('project_quotations as pq', 'pr.id=pq.request_id ')
            // ->join('company_master as company', 'company.company_id=pq.company_id')
                ->where("EXISTS (SELECT id FROM project_quotations WHERE request_id=pr.id AND project_quotations.status=" . QUOTATION_STATUS_QUOTED . " LIMIT 1)", null, false)
                ->where('projects.language_code', $params['language_code'])
                ->order_by('pr.id', 'DESC');

            if (isset($params['user_id']) && is_numeric($params['user_id'])) {
                $this->db->where('projects.user_id', $params['user_id']);
            }

            if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
                $this->db->limit((int)$params['limit']);
            }

            if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
                $this->db->offset((int)$params['offset']);
            }

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            $query = $this->db->get();
        // echo $this->db->last_query();die;
            $result['data'] = $query->result_array();
            $result['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

            return $result;
        }

        /**
         * Awaiting request query
         *
         * @param array $params
         * @return array
         */
        public function awaitingRequest($params)
        {
            $query = "SQL_CALC_FOUND_ROWS pr.id as request_id, project_id, name, number, levels, projects.address, lat, lng,
            customer.first_name as customer_name, pr.created_at as request_created_at,
            UNIX_TIMESTAMP(pr.created_at) as request_created_at_timestamp";

        // if (isset($params['type']) && (int)$params['type'] === AWAITING_REQUEST_TECHNICIAN) {
            $query .= ", GeoDistDiff('km', lat, lng, {$params['lat']}, {$params['lng']}) as distance";
        // $this->db->having('distance <=', REQUEST_SEARCH_RADIUS);
            $this->db->where("NOT EXISTS(SELECT pq.id FROM project_quotations as pq WHERE pq.request_id=pr.id AND pq.company_id={$params['company_id']} LIMIT 1)", null, false);
        // } else {
        // $this->db->where("NOT EXISTS(SELECT pq.id FROM project_quotations as pq WHERE request_id=pr.id LIMIT 1)", null, false);
        // }

            $this->db->select($query, false)
                ->from($this->tableName)
                ->join(
                    'project_request_installers as pri',
                    'pri.request_id=pr.id AND pri.company_id=' . $params['company_id']
                )
                ->join("projects", "pr.project_id=projects.id")
                ->join("ai_user as customer", "customer.user_id=projects.user_id")
                ->where('is_active', 1)
                ->where('pr.language_code', $params['language_code'])
                ->order_by('pr.id', 'DESC');

            if (isset($params['user_id']) && is_numeric($params['user_id'])) {
                $this->db->where('projects.user_id', $params['user_id']);
            }

            if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
                $this->db->limit((int)$params['limit']);
            }

            if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
                $this->db->offset((int)$params['offset']);
            }

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            $query = $this->db->get();
        // echo $this->db->last_query();die;
            $result['data'] = $query->result_array();
            $result['count'] = $this->db->query("SELECT FOUND_ROWS() as count")->row()->count;

            return $result;
        }

        /**
         * Get list of request which have been quoted
         *
         * @param array $params
         * @return array
         */
        public function quotedRequestList($params)
        {
            $fields = 'SQL_CALC_FOUND_ROWS pr.id as request_id, projects.name as project_name, user.first_name as customer_name,
            projects.address as project_address, projects.id as project_id, projects.lat as project_lat,
            projects.lng as project_lng, pr.created_at as request_created_at, projects.levels,
            pr.created_at_timestamp as request_created_at_timestamp,
            pqR.additional_product_charges, pq.discount,
            totalQuotationChargesPerRoom(projects.id, pq.company_id) as price, pq.status as quotation_status,
            pq.created_at as quotation_created_at,
            pq.created_at_timestamp as quotation_created_at_timestamp';

            $this->db->join(
                "project_quotations as pq",
                "pq.request_id=pr.id"
            );
            $this->db->select($fields, false)
                ->from('project_requests as pr')
                ->join('projects', 'projects.id=pr.project_id')
                ->join('ai_user as user', 'user.user_id=projects.user_id')
                ->where('pr.language_code', $params['language_code'])
                ->order_by("pq.id", "DESC");
            $this->db->where("(pq.status=" . QUOTATION_STATUS_QUOTED . " or pq.status=" . QUOTATION_STATUS_REJECTED . ")", null);

            if (isset($params['user_id']) && is_numeric($params['user_id'])) {
                $this->db->where('projects.user_id', $params['user_id']);
            }

            if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
                $this->db->limit((int)$params['limit']);
            }

            if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
                $this->db->offset((int)$params['offset']);
            }

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            $query = $this->db->get();

            $result['data'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

            return $result;
        }

        /**
         * Accepted Request List
         *
         * @param array $params
         * @return array
         */
        public function acceptedRequestList($params)
        {
            $fields = 'SQL_CALC_FOUND_ROWS pr.id as request_id, projects.name as project_name, 
            user.first_name as customer_name, projects.address as project_address, projects.id as project_id,
            projects.lat as project_lat, projects.lng as project_lng, pr.created_at as request_created_at,
            totalQuotationChargesPerRoom(projects.id, pq.company_id) as price, projects.levels,
            pq.additional_product_charges, pq.discount,
            pr.approved_at, pr.approved_at_timestamp,
            pr.created_at_timestamp as request_created_at_timestamp,
            pq.created_at as quotation_created_at,
            pq.created_at_timestamp as quotation_created_at_timestamp';

            $this->db->select($fields, false)
                ->from('project_quotations as pq')
                ->join('project_requests as pr', 'pr.id=pq.request_id')
                ->join('projects', 'projects.id=pr.project_id')
                ->join('ai_user as user', 'user.user_id=projects.user_id')
                ->where('pq.status', QUOTATION_STATUS_APPROVED)
                ->where('pq.company_id', $params['company_id'])
                ->where('pq.language_code', $params['language_code'])
                ->order_by("pq.id", "DESC");

            if (isset($params['user_id']) && is_numeric($params['user_id'])) {
                $this->db->where('projects.user_id', $params['user_id']);
            }

            if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
                $this->db->limit((int)$params['limit']);
            }

            if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
                $this->db->offset((int)$params['offset']);
            }

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            $query = $this->db->get();

            $result['data'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

            return $result;
        }

        /**
         * Get list of request which have been quoted
         *
         * @param array $params
         * @return array
         */
        public function submittedRequestList($params)
        {
            $fields = 'SQL_CALC_FOUND_ROWS pr.id as request_id, projects.name as project_name, user.first_name as customer_name,
            projects.address as project_address, projects.id as project_id, projects.lat as project_lat,
            projects.lng as project_lng, pr.created_at as request_created_at, projects.levels,
            pr.created_at_timestamp as request_created_at_timestamp, pq.status as quotation_status,
            pq.additional_product_charges, pq.discount,
            pq.created_at as quotation_created_at,
            pq.created_at_timestamp as quotation_created_at_timestamp';

            $this->db->join(
                "project_quotations as pq",
                "pq.request_id=pr.id"
            );
            $this->db->where('pq.company_id', $params['company_id']);
            $this->db->where("(pq.status=" . QUOTATION_STATUS_QUOTED . " or pq.status=" . QUOTATION_STATUS_REJECTED . ")", null);
            $this->db->select($fields, false)
                ->from('project_requests as pr')
                ->join('projects', 'projects.id=pr.project_id')
                ->join('ai_user as user', 'user.user_id=projects.user_id')
                ->where('pr.language_code', $params['language_code'])
                ->order_by("pr.id", "DESC");

            if (isset($params['user_id']) && is_numeric($params['user_id'])) {
                $this->db->where('projects.user_id', $params['user_id']);
            }

            if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
                $this->db->limit((int)$params['limit']);
            }

            if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
                $this->db->offset((int)$params['offset']);
            }

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            $query = $this->db->get();

            $result['data'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

            return $result;
        }

        /**
         * Accepted Request List
         *
         * @param array $params
         * @return array
         */
        public function approvedRequests($params)
        {
            $fields = 'SQL_CALC_FOUND_ROWS pr.id as request_id, projects.name as project_name, 
            user.first_name as customer_name, projects.address as project_address, projects.id as project_id,
            projects.lat as project_lat, projects.lng as project_lng, pr.created_at as request_created_at,
            projects.levels, pq.additional_product_charges, pq.discount, pr.approved_at, pr.approved_at_timestamp,
            pr.created_at_timestamp as request_created_at_timestamp, pq.created_at as quotation_created_at,
            pq.created_at_timestamp as quotation_created_at_timestamp';

            $this->db->select($fields, false)
                ->from('project_quotations as pq')
                ->join('project_requests as pr', 'pr.id=pq.request_id')
                ->join('projects', 'projects.id=pr.project_id')
                ->join('ai_user as user', 'user.user_id=projects.user_id')
                ->where('pq.status', QUOTATION_STATUS_APPROVED)
                ->where('pq.company_id', $params['company_id'])
                ->where('pq.language_code', $params['language_code'])
                ->order_by("pr.id", "DESC");

            if (isset($params['user_id']) && is_numeric($params['user_id'])) {
                $this->db->where('projects.user_id', $params['user_id']);
            }

            if (isset($params['limit']) && is_numeric($params['limit']) && (int)$params['limit'] > 0) {
                $this->db->limit((int)$params['limit']);
            }

            if (isset($params['offset']) && is_numeric($params['offset']) && (int)$params['offset'] > 0) {
                $this->db->offset((int)$params['offset']);
            }

            if (isset($params['where']) && is_array($params['where']) && !empty($params['where'])) {
                foreach ($params['where'] as $tableColumn => $searchValue) {
                    $this->db->where($tableColumn, $searchValue);
                }
            }

            $query = $this->db->get();

            $result['data'] = $query->result_array();
            $result['count'] = $this->db->query('SELECT FOUND_ROWS() as count')->row()->count;

            return $result;
        }
    }
