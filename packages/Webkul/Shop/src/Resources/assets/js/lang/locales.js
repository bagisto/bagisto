export const messages = {
    ar: {
        required     : (field) => 'حقل' + field + ' مطلوب',
        alpha        : (field) => field + ' يجب ان يحتوي على حروف فقط',
        alpha_num    : (field) => field + ' قد يحتوي فقط على حروف وارقام',
        min          : (field) => 'الحقل ' + field + ' يجب ان يحتوي على {length} حروف على الأقل',
        numeric      : (field) => field + ' يمكن ان يحتوي فقط على ارقام',
        oneOf        : (field) => 'الحقل ' + field + 'يجب ان يكون قيمة صحيحة',
        regex        : (field) => 'الحقل' + field+ ' غير صحيح',
        required_if  : (field) => 'حقل' + field + ' مطلوب',
        size         : (field) => field + ' يجب ان يكون اقل من {size} كيلوبايت',
        min_value    : (field) => 'قيمة الحقل' + field + ' يجب ان تكون اكبر من {min} او تساويها',
        alpha_spaces : (field) => field + ' قد يحتوي فقط على حروف ومسافات',
        between      : (field) => 'قيمة ' +field+ ' يجب ان تكون ما بين {min} و {max}',
        confirmed    : (field) => field + ' لا يماثل التأكيد',
        digits       : (field) => field + ' يجب ان تحتوي فقط على ارقام والا يزيد عددها عن {length} رقم',
        dimensions   : (field) => field + ' يجب ان تكون بمقاس {width} بكسل في {height} بكسل',
        email        : (field) => field + ' يجب ان يكون بريدا اليكتروني صحيح',
        excluded     : (field) => 'الحقل' + field +'غير صحيح',
        ext          : (field) =>'نوع مل'+ field + 'غير صحيح',
        image        : (field) => field + ' يجب ان تكون صورة',
        integer      : (field) => 'الحقل ' +field + ' يجب ان يكون عدداً صحيحاً',
        length       : (field) => 'حقل'+ field + ' يجب الا يزيد عن {length}',
        max_value    : (field) => 'قيمة الحقل '+ field + ' يجب ان تكون اصغر من {min} او تساويها',
        max          : (field) => 'الحقل' + field + 'يجب ان يحتوي على {length} حروف على الأكثر',
        mimes        : (field) => 'نوع ملف' + field + 'غير صحيح'
    }
}
