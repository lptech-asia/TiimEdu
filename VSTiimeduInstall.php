<?php
/**
 * Class VSTiimeduInstall
 * @todo This's the module create automatic by terminal
 * @author LPTech Terminal <tech@lptech.asia>
 * @since 11/02/2025 03:38:19
 */

class VSTiimeduInstall extends VSInstaller implements VSInstallerInterface
{
    private static $__instance = null;
    public static function getInstance()
    {
        if (null === self::$__instance) {
            self::$__instance = new self();
        }

        return self::$__instance;
    }

    public function __construct()
    {
        $this->_setTitle("Tiimedu");
        $this->_setDescription("Tiimedu  - Module quản lý hồ sơ đăng ký du học cho website tuvanduhoc.org");
        $this->_setClass($this);
        $this->_setVersion("1.0");
        $this->_setCMSVersion("10.0");
        // add Dependency
        $this->_setDependencies(['Admin', 'User']);
        parent::__construct();
    }

    public function init()
    {
        // Adding menu
        $this->addMenu('TiimEdu', 'tiimedu')->setIcon('graduation-cap')
        ;

        // Table schema
        $this->_installerData->setVersion("1.0")
            ->addTable('tiimedu_users')->setPrimaryKey('id')->setInt(11)->unsigned()
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                ->addColumn('type')->setInt(1)->notNull()->defaultValue(1)->addComment('1 :Student , 2: Schools')
                ->addCreatedAt()->addUpdatedAt()
                // add foreign key
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')
            // table user information
            ->addTable('tiimedu_student_information')->setPrimaryKey('id')->setInt(11)->unsigned()
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                // univercity name
                ->addColumn('university_name')->setVarchar(255)->addComment('Tên trường')
                // major name
                ->addColumn('major_name')->setVarchar(255)->addComment('Tên ngành')
                // degree
                ->addColumn('degree')->setVarchar(255)->addComment('Bằng cấp')
                // gpa
                ->addColumn('gpa')->setVarchar(255)->addComment('Điểm trung bình')
                // passport no
                ->addColumn('identify_number')->setVarchar(255)->addComment('Số CMND/CCCD')
                // identify name
                ->addColumn('identify_name')->setVarchar(255)->addComment('Họ và tên trên CMND/CCCD')
                // Date of birth
                ->addColumn('date_of_birth')->setDate()->addComment('Ngày sinh')
                // Gender
                ->addColumn('gender')->setInt(1)->addComment('Nam: 1 /Nữ: 2 / Khác: 3')
                // address
                ->addColumn('identify_address')->setVarchar(255)->addComment('Địa chỉ')
                //  identify front image
                ->addColumn('identify_front_image')->setVarchar(255)->addComment('Ảnh mặt trước CMND/CCCD')
                // identify back image
                ->addColumn('identify_back_image')->setVarchar(255)->addComment('Ảnh mặt sau CMND/CCCD')
                // passport number
                ->addColumn('passport_number')->setVarchar(255)->addComment('Số hộ chiếu')
                // passport image
                ->addColumn('passport_image')->setVarchar(255)->addComment('Ảnh hộ chiếu')
                // Expired date
                ->addColumn('passport_expired_at')->setDate()->addComment('Ngày hết hạn hộ chiếu')
                // Issue date
                ->addColumn('passport_issue_at')->setDate()->addComment('Ngày cấp hộ chiếu')
                // Nationality 
                ->addColumn('passport_nationality')->setVarchar(255)->addComment('Quốc tịch')
                // updated by
                ->addColumn('updated_by')->setInt(11)->unsigned()->addComment('Id người cập nhật')
                ->addCreatedAt()->addUpdatedAt()
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')

            // table country
            ->addTable('tiimedu_countries')->setPrimaryKey('id')->setInt(11)->unsigned()
                // country name
                ->addColumn('name')->setVarchar(255)->addComment('Tên quốc gia')
                // country code
                ->addColumn('code')->setVarchar(255)->addComment('Mã quốc gia')
                // image
                ->addColumn('image')->setVarchar(255)->addComment('Ảnh quốc gia')
                // description
                ->addColumn('description')->setText()->addComment('Mô tả quốc gia')
            
            // table school information
            ->addTable('tiimedu_school_information')->setPrimaryKey('id')->setInt(11)->unsigned()
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                // school name
                ->addColumn('name')->setVarchar(255)->addComment('Tên trường')
                // school type
                ->addColumn('type')->setInt(1)->addComment('Loại trường Public Or Private')
                // school level
                ->addColumn('sku')->setVarchar(100)->addComment('Mã trường')
                // school phone
                ->addColumn('phone')->setVarchar(25)->addComment('Số điện thoại trường')
                // school email
                ->addColumn('email')->setVarchar(150)->addComment('Email trường')
                // school website
                ->addColumn('website')->setVarchar(50)->addComment('Website trường')
                // school logo
                ->addColumn('logo')->setVarchar(255)->addComment('Logo trường')
                // school description
                ->addColumn('description')->setVarchar(500)->addComment('Mô tả trường')
                // school address
                ->addColumn('address')->setVarchar(255)->addComment('Địa chỉ trường')
                // school province
                ->addColumn('country_id')->setInt(1)->unsigned()->addComment('ID Quốc gia')
                // school district
                ->addColumn('city')->setVarchar(255)->addComment('Thành phố')
                // school image
                ->addColumn('image_folder')->setVarchar(255)->addComment('Folder Ảnh trường')
                // Found Year
                ->addColumn('found_year')->setInt(4)->addComment('Năm thành lập')
                // Enrollment Email
                ->addColumn('enrollment_email')->setVarchar(300)->addComment('Email tuyển sinh')
                // Accountant Email
                ->addColumn('accountant_email')->setVarchar(300)->addComment('Email kế toán')
                // Management Email
                ->addColumn('management_email')->setVarchar(300)->addComment('Email quản lý')
                // Brochure
                ->addColumn('brochure')->setVarchar(255)->addComment('Brochure')
                // Campus Residence
                ->addColumn('campus_residence')->setVarchar(500)->addComment('Ký túc xá')
                // Lastest QS Ranking
                ->addColumn('lastest_qs_ranking')->setInt(1)->addComment('Xếp hạng QS gần nhất')
                // Lastest Shanghai Ranking
                ->addColumn('lastest_shanghai_ranking')->setInt(1)->addComment('Xếp hạng Shanghai gần nhất')
                // Lastest National Ranking
                ->addColumn('lastest_national_ranking')->setInt(1)->addComment('Xếp hạng quốc gia gần nhất')
                // updated by
                ->addColumn('updated_by')->setInt(11)->unsigned()->addComment('Id người cập nhật')
                ->addCreatedAt()->addUpdatedAt()
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                // add add Foreign Key country
                ->addForeignKey('country_id', 'id', 'tiimedu_countries')->fkDelete('CASCADE')->fkUpdate('CASCADE')

                // table Living option
            ->addTable('tiimedu_living_options')->setPrimaryKey('id')->setInt(11)->unsigned()
                // school id
                ->addColumn('school_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng school')
                // description
                ->addColumn('content')->setVarchar(255)->addComment('Mô tả')
                // status
                ->addColumn('status')->setInt(1)->notNull()->defaultValue(1)->addComment('Trạng thái')
                // add add Foreign Key
                ->addForeignKey('school_id','id', 'tiimedu_school_information')->fkDelete('CASCADE')->fkUpdate('CASCADE')
            // table Program
            ->addTable('tiimedu_programs')->setPrimaryKey('id')->setInt(11)->unsigned()
                // school id
                ->addColumn('school_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng school')
                // University Name
                ->addColumn('university_name')->setVarchar(255)->addComment('Tên trường')
                // Program ID
                ->addColumn('program_id')->setVarchar(255)->addComment('Mã chương trình')
                // Degree
                ->addColumn('degree')->setVarchar(255)->addComment('Loại hình Bằng cấp')
                // Program Name
                ->addColumn('program_name')->setVarchar(255)->addComment('Tên chương trình')
                // Duration
                ->addColumn('duration')->setVarchar(255)->addComment('Thời gian học')
                // Fee Year1
                ->addColumn('fee_year1')->setVarchar(255)->addComment('Học phí năm 1')
                // Fee Year2
                ->addColumn('fee_year2')->setVarchar(255)->addComment('Học phí năm 2')
                // Fee Year3
                ->addColumn('fee_year3')->setVarchar(255)->addComment('Học phí năm 3')
                // Fee Year4
                ->addColumn('fee_year4')->setVarchar(255)->addComment('Học phí năm 4')
                // Fee Year5
                ->addColumn('fee_year5')->setVarchar(255)->addComment('Học phí năm 5')
                // Fee Year6
                ->addColumn('fee_year6')->setVarchar(255)->addComment('Học phí năm 6')
                // Whole Program Fee
                ->addColumn('whole_program_fee')->setVarchar(255)->addComment('Học phí toàn khóa')
                // additional fee
                ->addColumn('additional_fee')->setVarchar(500)->addComment('Phí khác')
                // Language Required
                ->addColumn('language_required')->setVarchar(255)->addComment('Yêu cầu ngôn ngữ:  1 Có / 0: Không')
                // GPA Required
                ->addColumn('gpa_required')->setVarchar(255)->addComment('Yêu cầu GPA:  1 Có / 0: Không')
                // CV & Motivation Letter
                ->addColumn('cv_motivation_letter')->setVarchar(255)->addComment('CV & Thư động viên:  1 Có / 0: Không')
                // Referent Letter
                ->addColumn('referent_letter')->setInt(1)->addComment('Thư giới thiệu:  1 Có / 0: Không')
                // Interview
                ->addColumn('interview')->setVarchar(255)->addComment('Phỏng vấn 1: Có / 0: Không')
                // Finance Proof
                ->addColumn('finance_proof')->setVarchar(255)->addComment('Chứng minh tài chính 1: Có / 0: Không')
                //Intake1
                ->addColumn('intake1')->setVarchar(255)->addComment('Kỳ nhập học 1')
                // Deadline to apply1
                ->addColumn('deadline1')->setVarchar(255)->addComment('Hạn chót nộp hồ sơ 1')
                // Intake2
                ->addColumn('intake2')->setVarchar(255)->addComment('Kỳ nhập học 2')
                // Deadline to apply2
                ->addColumn('deadline2')->setVarchar(255)->addComment('Hạn chót nộp hồ sơ 2')
                // Intake3
                ->addColumn('intake3')->setVarchar(255)->addComment('Kỳ nhập học 3')
                // Acceptance Rate
                ->addColumn('acceptance_rate')->setVarchar(255)->addComment('Tỷ lệ chấp nhận')
                // International Students
                ->addColumn('international_students')->setVarchar(255)->addComment('Số lượng sinh viên quốc tế')
                // updated by
                ->addColumn('updated_by')->setInt(11)->unsigned()->addComment('Id người cập nhật')
                ->addCreatedAt()->addUpdatedAt()

                ->addForeignKey('school_id', 'id', 'tiimedu_school_information')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                

            // add table scholarship
            ->addTable('tiimedu_scholarships')->setPrimaryKey('id')->setInt(11)->unsigned()
                // school id
                ->addColumn('program_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng school')
                // name
                ->addColumn('name')->setVarchar(255)->addComment('Tên học bổng')
                // description
                ->addColumn('description')->setVarchar(500)->addComment('Mô tả')
                ->addCreatedAt()->addUpdatedAt()
                // add foreign key
                ->addForeignKey('program_id', 'id', 'tiimedu_programs')->fkDelete('CASCADE')->fkUpdate('CASCADE')

            // Document of type
            ->addTable('tiimedu_document_types')->setPrimaryKey('id')->setInt(11)->unsigned()
                // name
                ->addColumn('name')->setVarchar(255)->notNull()->addComment('Tên loại')
                // description
                ->addColumn('description')->setVarchar(255)->addComment('Mô tả')
                // status
                ->addColumn('status')->setInt(1)->notNull()->defaultValue(1)->addComment('Trạng thái')
            // Documents
            ->addTable('tiimedu_documents')->setPrimaryKey('id')->setInt(11)->unsigned()
                // type
                ->addColumn('type_id')->setInt(1)->unsigned()->addComment('Xem ở bảng type')
                // user id
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                // name
                ->addColumn('name')->setVarchar(255)->notNull()->addComment('Tên tài liệu')
                // description
                ->addColumn('description')->setVarchar(255)->addComment('Mô tả')
                // file attachment
                ->addColumn('file')->setVarchar(255)->notNull()->addComment('File đính kèm')
                ->addStatus()->addCreatedAt()->addUpdatedAt()
                // add add Foreign Key
                ->addForeignKey('type_id', 'id', 'tiimedu_document_types')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                // add add Foreign Key
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')
            // table event checkin
            ->addTable('tiimedu_event_checkin')->setPrimaryKey('id')->setInt(11)->unsigned()
                // school id
                ->addColumn('school_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng school')
                // user id
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                // status view checkin 1: đã xem / 0: chưa xem
                ->addColumn('view')->setInt(1)->notNull()->defaultValue(0)->addComment('Trạng thái checkin 0: Chưa xem / 1: Đã zem')
                // viewed by
                ->addColumn('viewed_by')->setInt(11)->unsigned()->addComment('Id người xem là id user ở loại trường học')
                ->addCreatedAt()->addUpdatedAt()
                // add add Foreign Key school_id
                ->addForeignKey('school_id', 'id', 'tiimedu_school_information')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                // add add Foreign Key user_id
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')
            // add table viewed university
            ->addTable('tiimedu_school_viewed')->setPrimaryKey('id')->setInt(11)->unsigned()
                // school id
                ->addColumn('school_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng school')
                // user id
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                // counter view
                ->addColumn('counter')->setInt(11)->unsigned()->notNull()->defaultValue(1)->addComment('Số lần xem')
                ->addCreatedAt()->addUpdatedAt()
                // add add Foreign Key school_id
                ->addForeignKey('school_id', 'id', 'tiimedu_school_information')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                // add add Foreign Key user_id
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')
            // add table application
            ->addTable('tiimedu_applications')->setPrimaryKey('id')->setInt(11)->unsigned()
                // program id
                ->addColumn('program_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng program')
                // school_id
                ->addColumn('school_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng school')
                // user id
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                // name
                ->addColumn('name')->setVarchar(255)->addComment('Tên hồ sơ ứng tuyển')
                // scholarship id
                ->addColumn('scholarship_id')->setInt(11)->unsigned()->addComment('Id bảng scholarship')
                // Scholarship Essay
                ->addColumn('scholarship_essay')->setText(255)->addComment('Bài luận học bổng')
                ->addColumn('scholarship_essay_attachment')->setVarchar(250)->addComment('URL File Bài luận học bổng')
                // cover letter
                ->addColumn('cover_letter')->setText()->addComment('Thư xin học bổng')
                ->addColumn('cover_letter_attachment')->setVarchar(250)->addComment('URL File Thư xin học bổng')
                // status
                ->addColumn('status')->setInt(1)->notNull()->defaultValue(0)->addComment('Trạng thái ứng tuyển 0: Chưa xem / 1: Đã xem / 2: Đã duyệt / 3: Đã từ chối')
                // viewed by
                ->addColumn('viewed_by')->setInt(11)->unsigned()->addComment('Id người xem là id user ở loại trường học')
                ->addCreatedAt()->addUpdatedAt()
                // add add Foreign Key program_id
                ->addForeignKey('program_id', 'id', 'tiimedu_programs')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                // add add Foreign Key school_id
                ->addForeignKey('school_id', 'id', 'tiimedu_school_information')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                // add add Foreign Key user_id
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                
            // add table conversation
            ->addTable('tiimedu_conversations')->setPrimaryKey('id')->setInt(11)->unsigned()
                // application id
                ->addColumn('application_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng application')
                // school id
                ->addColumn('school_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng school')
                // user id
                ->addColumn('user_id')->setInt(11)->unsigned()->notNull()->addComment('Id bảng users')
                // author name
                ->addColumn('author_name')->setVarchar(150)->addComment('Tên người gửi')
                // message
                ->addColumn('message')->setVarchar(500)->notNull()->addComment('Nội dung tin nhắn')
                // attachment
                ->addColumn('attachment')->setVarchar(255)->addComment('URL File đính kèm')
                // status view checkin 1: đã xem / 0: chưa xem
                ->addColumn('view')->setInt(1)->notNull()->defaultValue(0)->addComment('Trạng thái tin nhắn 0: Chưa xem / 1: Đã zem')
                ->addCreatedAt()->addUpdatedAt()
                // add add Foreign Key school_id
                ->addForeignKey('school_id', 'id', 'tiimedu_school_information')->fkDelete('CASCADE')->fkUpdate('CASCADE')
                // add add Foreign Key user_id
                ->addForeignKey('user_id', 'id', 'users')->fkDelete('CASCADE')->fkUpdate('CASCADE')
        ;
        parent::_init();
    }

    public function createData(){}
}