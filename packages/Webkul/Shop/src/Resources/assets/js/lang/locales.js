export const messages = {
    ar: {
        required     : (field) => 'حقل' + field + ' مطلوب',
        alpha        : (field) => field + ' يجب ان يحتوي على حروف فقط',
        alpha_num    : (field) => field + ' قد يحتوي فقط على حروف وارقام',
        min          : (field, length) => 'الحقل ' + field + ' يجب ان يحتوي على ' + length + ' حروف على الأقل',
        numeric      : (field) => field + ' يمكن ان يحتوي فقط على ارقام',
        oneOf        : (field) => 'الحقل ' + field + 'يجب ان يكون قيمة صحيحة',
        regex        : (field) => 'الحقل' + field+ ' غير صحيح',
        required_if  : (field) => 'حقل' + field + ' مطلوب',
        size         : (field, size) => field + ' يجب ان يكون اقل من ' + size + ' كيلوبايت',
        min_value    : (field, min) => 'قيمة الحقل' + field + ' يجب ان تكون اكبر من ' + min + ' او تساويها',
        alpha_spaces : (field) => field + ' قد يحتوي فقط على حروف ومسافات',
        between      : (field, min, max) => 'قيمة ' +field+ ' يجب ان تكون ما بين ' + min + ' و ' + max,
        confirmed    : (field) => field + ' لا يماثل التأكيد',
        digits       : (field, length) => field + ' يجب ان تحتوي فقط على ارقام والا يزيد عددها عن ' + length + ' رقم',
        dimensions   : (field, width, height) => field + ' يجب ان تكون بمقاس ' + width + ' بكسل في ' + height + ' بكسل',
        email        : (field) => field + ' يجب ان يكون بريدا اليكتروني صحيح',
        excluded     : (field) => 'الحقل' + field +'غير صحيح',
        ext          : (field) =>'نوع مل'+ field + 'غير صحيح',
        image        : (field) => field + ' يجب ان تكون صورة',
        integer      : (field) => 'الحقل ' +field + ' يجب ان يكون عدداً صحيحاً',
        length       : (field, length) => 'حقل'+ field + ' يجب الا يزيد عن ' + length,
        max_value    : (field, min) => 'قيمة الحقل '+ field + ' يجب ان تكون اصغر من ' + min + ' او تساويها',
        max          : (field, length) => 'الحقل' + field + 'يجب ان يحتوي على ' + length + ' حروف على الأكثر',
        mimes        : (field) => 'نوع ملف' + field + 'غير صحيح'
    }
}
