//============================================================================
// Name        : test4.cpp
// Author      : 
// Version     :
// Copyright   : Your copyright notice
// Description : Hello World in C++, Ansi-style
//============================================================================

#include <iostream>
#include <stdlib.h>
#include <string>
#include <queue>
#include <stack>
using namespace std;

int main() {

	string holder;
	queue<char> qholder;
	queue<char> qholder2;
	stack<char> sholder;

	cout << "please enter the string you wish to reverse" << endl;
	cin >> holder;

	int y = holder.length();

	cout << "!!!the string is : " << holder << endl; // prints !!!Hello World!!!

	//cout <<holder[4]<<"!!!Hello!!!" << y <<endl;

	for (int k = 0; k < y; k++) {
		qholder.push(holder[k]);
		// cout<<" The queued  letter :"<<qholder.back();
	}
	cout << endl;

	while (!qholder.empty()) {
		sholder.push(qholder.front());
		// cout<<qholder.front();
		qholder.pop();
		//cout<< " The top of the stack is "<<sholder.top();
	}
	cout << endl;
	while (!sholder.empty()) {
		//cout<<sholder.top();
		qholder.push(sholder.top());
		sholder.pop();
	}

	for (int k = 0; k < y; k++) {

		holder[k] = qholder.front();
		qholder.pop();
		//cout<< k<<  endl;
	}
	cout << " The reversed string is " << holder;

	return 0;
}
