  /* C++ program to Find Largest of three numbers using nested if  */

#include<iostream>
using namespace std;

int main()
{

    int a, b, c;
    cout <<"Enter 1st number :: ";
    cin>>a;
    cout <<"\nEnter 2nd number :: ";
    cin>>b;
    cout <<"\nEnter 3rd number :: ";
    cin>>c;

    if(a>=b && a>=c)
    {
    cout<<"\nThe Largest number among [ "<<a<<", "<<b<<", "<<c<<" ] is :: "<<a<<"\n";
    }
    if(b>=a && b>=c)
    {
    cout<<"\nThe Largest number among [ "<<a<<", "<<b<<", "<<c<<" ] is :: "<<b<<"\n";
    }
    if(c>=a && c>=b)
    {
    cout<<"\nThe Largest number among [ "<<a<<", "<<b<<", "<<c<<" ] is :: "<<c<<"\n";
    }

   return 0;
}
